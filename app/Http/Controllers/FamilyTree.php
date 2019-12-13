<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * @return mixed
     */
    public function getMembers() {
        return Member::orderBy('name', 'asc')->get();
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function createMember(Request $request){
        $birthday = new \DateTime($request->input("birthday"));
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthday' => 'required',
        ]);
        if ($validator->fails()) {
            return ['errors' => $validator->errors()->toArray()];
        }
        if($member = Member::where(['name' => $request->input("name"), 'birthday' => $birthday->format('m/d/Y')])->first()){
            return ['errors' => ['errors' => ['Family member already exists!']]];
        }
        $date = new \DateTime($request->input("birthday"));
        $now = new \DateTime();
        $age = $now->diff($date);
        $member = new Member([
            "age" => $age->y,
            "name" => $request->input("name"),
            "birthday" => $birthday->format('m/d/Y'),
            "phone_number" => $request->input("phoneNumber"),
            "email" => $request->input("email"),
            "address" => "",
            "parents" => "",
            "spouse" => "",
            "children" => ""
        ]);
        $member->save();
        return $response = ['success' =>'Family Member Successfully Added!'];
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMember(Request $request){
        $this->validate($request,[
           'main' => 'required',
           'mother' => 'required',
           'father' => 'required'
        ], [
            'main.required' => 'A Family Member Must Be Selected!',
            'mother.required' => 'A Mother Must Be Selected!',
            'father.required' => 'A Father Must Be Selected!'
        ]);

        $main = $request->input('main');
        $motherInitial = $request->input('mother');
        $fatherInitial = $request->input('father');
        $spouseInitial = $request->input('spouse');

        $member = Member::where(['id' => $main['id']])->first();
        if($member->parents == "") {
            $mother = Member::where(['id' => $motherInitial['id'],])->first();

            $mother->children = $mother->children . ' ' . $member->id;
            $mother->save();

            $father = Member::where(['id' => $fatherInitial['id']])->first();

            $father->children = $father->children . ' ' . $member->id;
            $father->save();

            $member->parents = $father->id . ' ' . $mother->id;
            $member->save();
        } else {
            $parents = explode(" ", $member->parents);
            $father = Member::where('id',$parents[0])->first();
            $mother = Member::where('id',$parents[1])->first();
            return $response = ['error' => 'Family Member Already Has Parents: '.$father->name.' And '.$mother->name];
        }

        if($member->spouse == "" ) {
            if($request->filled('spouse')) {
                $spouse = Member::where(['name' => $spouseInitial['name'], 'birthday' => $spouseInitial['birthday']])->first();
                $spouse->spouse = $member->id;
                $member->spouse = $spouse->id;
                $spouse->save();
                $member->save();
            }
        } else {
            $spouse = Member::where('id',$member->spouse)->first();
            return $response = ['error'=>'Family Member Already Has A Spouse: '.$spouse->name.' '.$spouse->birthday];
        }

        $member->update([
            'phone_number' => $request->input("phoneNumber"),
            'address' => $request->input("address"),
            'email' => $request->input("email")
        ]);

        return ['success' => 'Family Member Successfully Updated!'];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getBirthdays() {
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
