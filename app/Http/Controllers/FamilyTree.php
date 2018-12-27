<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class FamilyTree extends Controller
{
    /**
     * @return array|mixed
     */
    public function viewTree(){
        $spouses = $this->Spouses();
        $families = $this->Children($spouses);
        for($i=0;$i<count($families);$i++){
            if(array_key_exists('children',$families[$i])){
                usort($families[$i]['children'], function ($a, $b) {
                    return new \DateTime($a['birthday']) <=> new \DateTime($b['birthday']);
                });
            }
        }
        return $families;
    }

    /**
     * Foreach member find if they have a spouse
     * @return array
     */
    protected function Spouses(){
        $members = Member::all()->toArray();
        usort($members, function ($a, $b) {
            return new \DateTime($a['birthday']) <=> new \DateTime($b['birthday']);
        });
        $spouses = [];
        $has_spouse = array_filter($members, function($member){
            return $member['spouse'] != '' || $member['children'] != '';
        });
        $checked = [];
        $has_spouse = array_values($has_spouse);
        for($i=0; $i < count($has_spouse); $i++){
            for($j=0; $j < count($has_spouse); $j++){
                if(intval($has_spouse[$i]['spouse']) == intval($has_spouse[$j]['id']) && !in_array(intval($has_spouse[$i]['id']),$checked) && !in_array(intval($has_spouse[$j]['id']),$checked)){
                    if($has_spouse[$i]['children'] == ''){
                        array_push($spouses,array('couple' => [$has_spouse[$i],$has_spouse[$j]]));
                    }else{
                        array_push($spouses,array('parents' => [$has_spouse[$i],$has_spouse[$j]], 'children' => []));
                    }
                    array_push($checked,intval($has_spouse[$i]['id']));
                    array_push($checked,intval($has_spouse[$j]['id']));
                }elseif(intval($has_spouse[$i]['spouse']) == '' && $has_spouse[$i]['children'] != '' && !in_array(intval($has_spouse[$i]['id']),$checked)){
                    array_push($spouses,array('parents' => [$has_spouse[$i]], 'children' => []));
                    array_push($checked,intval($has_spouse[$i]['id']));
                }
            }
        }
        return $spouses;
    }

    /**
     * Foreach couple find if they have children
     * @param $spouses
     * @return mixed
     */
    protected function Children($spouses){
        for($i=0;$i<count($spouses);$i++){
            if(array_key_exists('children',$spouses[$i])){
                $children_string = trim($spouses[$i]['parents'][0]['children']);
                $children = explode(" ",$children_string);
                foreach ($children as $child_id){
                    array_push($spouses[$i]['children'], Member::where('id',intval($child_id))->first()->toArray());
                }
            }
        }
        return $spouses;
    }

    /**
     * used for returning the update page with all family members
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function startUpdate(){
        $members = Member::all();
        $members = $members->toArray();
        $sort_col = array();
        foreach ($members as $key=> $row) {
            $sort_col[$key] = $row['name'];
        }
        array_multisort($sort_col, SORT_ASC, $members);
        return view('updateFamilyTree')->with('members',$members);


    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function createMember(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthday' => 'required',
            'phoneNumber' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()->toArray()];
        }
        if($member = Member::where(['name' => $request->input("name"), 'birthday' => $request->input("birthday")])->first()){
            return ['errors' => ['errors' => ['Family member already exists!']]];
        }
        $date = new \DateTime($request->input("birthday"));
        $now = new \DateTime();
        $age = $now->diff($date);
        $member = new Member([
            "age" => $age->y,
            "name" => $request->input("name"),
            "birthday" => $request->input("birthday"),
            "phone_number" => $request->input("phoneNumber"),
            "email" => $request->input("email")
        ]);
        $member->save();
        return $response = ['success' =>'Family Member Successfully Added!'];
    }

    /**
     * Updates a member
     * @param Request $request
     * @return array
     */
    public function updateMember(Request $request){
        $members = Member::all();
        $members = $members->toArray();
        $sort_col = array();
        foreach ($members as $key=> $row) {
            $sort_col[$key] = $row['name'];
        }
        array_multisort($sort_col, SORT_ASC, $members);

        $memberNameBirthDay = explode('Birthday:',$request->input("name"));
        $motherNameBirthDay = explode('Birthday:',$request->input("mother"));
        $fatherNameBirthDay = explode('Birthday:',$request->input("father"));
        $spouseNameBirthDay = explode('Birthday:',$request->input("spouse"));
        if($memberNameBirthDay[0] != "default"){
            $memberNameBirthDay[0] = trim($memberNameBirthDay[0]);
            $memberNameBirthDay[1] = trim($memberNameBirthDay[1]);
            $member = Member::where(['name' => $memberNameBirthDay[0], 'birthday' => $memberNameBirthDay[1]])->first();
            if($member){
                if($motherNameBirthDay[0] != "default"){
                    if($member->parents == "") {
                        $motherNameBirthDay[0] = trim($motherNameBirthDay[0]);
                        $motherNameBirthDay[1] = trim($motherNameBirthDay[1]);
                        $mother = Member::where(['name' => $motherNameBirthDay[0], 'birthday' => $motherNameBirthDay[1]])->first();

                        $mother->children = $mother->children . ' ' . $member->id;
                        $mother->save();
                    }else{
                        $parents = explode(" ",$member->parents);
                        $father = Member::where('id',$parents[0])->first();
                        $mother = Member::where('id',$parents[1])->first();
                        return $response = ['error' => 'Family Member Already Has Parents: '.$father->name.' And '.$mother->name];
                    }
                }
                if($fatherNameBirthDay[0] != "default"){
                    if($member->parents == "") {
                        $fatherNameBirthDay[0] = trim($fatherNameBirthDay[0]);
                        $fatherNameBirthDay[1] = trim($fatherNameBirthDay[1]);
                        $father = Member::where(['name' => $fatherNameBirthDay[0], 'birthday' => $fatherNameBirthDay[1]])->first();

                        $father->children = $father->children . ' ' . $member->id;
                        $father->save();
                    }else{
                        $parents = explode(" ",$member->parents);
                        $father = Member::where('id',$parents[0])->first();
                        $mother = Member::where('id',$parents[1])->first();
                        return $response = ['error' => 'Family Member Already Has Parents: '.$father->name.' And '.$mother->name];
                    }
                }
                if($spouseNameBirthDay[0] != "default"){
                    if($member->spouse == ""){
                        $spouseNameBirthDay[0] = trim($spouseNameBirthDay[0]);
                        $spouseNameBirthDay[1] = trim($spouseNameBirthDay[1]);
                        $spouse = Member::where(['name' => $spouseNameBirthDay[0], 'birthday' => $spouseNameBirthDay[1]])->first();
                        $spouse->spouse = $member->id;
                        $member->spouse = $spouse->id;
                        $spouse->save();
                        $member->save();
                    }
                    else{
                        $spouse = Member::where('id',$member->spouse)->first();
                        return $response = ['error'=>'Family Member Already Has A Spouse: '.$spouse->name.' '.$spouse->birthday];
                    }
                }
                if($request->input("phoneNumber") != ''){
                    $member->phone_number = $request->input("phoneNumber");
                    $member->save();
                }
                if($request->input("address") != ''){
                    $member->address = $request->input("address");
                    $member->save();
                }
                if($request->input("email") != ''){
                    $member->email = $request->input("email");
                    $member->save();
                }
                return $response = ['success' => 'Family Member Successfully Updated!'];
            }else{
                return $response = ['error' => 'A Family Member Must Be Entered!'];
            }
        }
        return $response = ['error' => 'A Family Member Must Be Entered!'];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getBirthdays(){
        $members = Member::all();
        $birthdays =[];
        foreach ($members as $member){
            $check = substr($member->birthday,0, -5);
            $age = $this->getAge($member);
            $member->age = $age;
            $member->save();
            $month = date('m');
            $day = date('d');
            $now = $month."/".$day;
            if($now == $check){
                array_push($birthdays,$member->toArray());
            }
        }
        return $birthdays;
    }

    /**
     * @param $member
     * @return int
     * @throws \Exception
     */
    public function getAge($member){
        $date = new \DateTime($member->birthday);
        $now = new \DateTime();
        $age = $now->diff($date);
        return $age->y;
    }
}
