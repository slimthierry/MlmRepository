<?php

namespace App\Http\Controllers\Api;

use App\Abstracts\Http\ApiController;
use App\Exceptions\MemberNotBelongsToUser;
use App\Http\Resources\ClientMembershipResource;
use App\Models\Membership;
use App\Repositories\MembershipRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Mail;
// use App\Mail\SendCode;

class MemberShipsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $MembershipRepository;

    private $levels = array('tree'=>[]);

    private $finallevel = 0;

        /**
     * Create a new MemberController instance.
     *
     * @param \App\Repositories\MembershipRepository $MembershipRepository
     * @return void
     */
    public function __construct(MembershipRepository $MembershipRepository)
    {
    $this->MembershipRepository = $MembershipRepository;
}

/**
 * Login
 * @return [type] [description]
 */
    public function index()
    {

        // return ClientMembershipResource::collection(Membership::paginate(10));

        $member = Membership::all();
        $response =[
            'msg' => 'List of all Memberships',
            'membership' =>$member
        ];

        return response()->json($response, 200);
    }
    // }

    /**
     * Show the form for creating a new member.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
        {
                $this->validate($request,[
                'phone_number'=>'required|min:8|numeric',
                'email'=>'required|email'
            ]);
            $id =$request->input('id');
            $parrain_id =$request->input('parrain_id');
            $username =$request->input('username');
            $phone_number=$request->input('phone_number');
            $email =$request->input('email');
            $code =$this->generateKey();
            // $member_level =$request->input('member_level');
            $created_at =$request->input('created_at');

            $member = new Membership([
                'id' => $id,
                'parrain_id'=>$parrain_id,
                'username'=> $username,
                'phone_number' =>$phone_number,
                'email' =>$email,
                'code'=> $code,
                'member_level' => 0,
                'created_at' => $created_at
            ]);

                if ($member->save()) {[
                    # code...
                    'method' => 'POST',
                    'params' => 'phone_number, email'
                ];
                $response=[
                    'msg' => 'Membership created',
                    'member' => $member
                ];
                return response()->json($response , 201);
                    }
                $response =
                    [
                        'msg' => 'An error occured'
                    ];
                return response()->json($response , 404);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Membership $member, $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $parrain_id, $member)
    {
        $count = 0;
        $this->MemberUserCheck($member);
        $request['parrain_id'] = $request->parrain_id;
        unset($request['parrain_id']);
        $member->update($request->all());
            return response([
                'data' => new ClientMembershipResource($member)], Response::HTTP_CREATED);

             $results = Membership::where('id', '=', $parrain_id)->get();
                for ($i=0; $i < 10; $i++) {
                     # code...
                     $var = str_repeat(' ', $this->levels) . $results->parrain . "\n";
                     $count += 1 + $this->getChildren($results->parrain, $this->levels + 1);
                 }
             return $var;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $Member = Membership::findOrfail($id);
        $Member->delete();

        return response()->json(null, 204);
    }

    public function generateKey()
        {
            $KeyLength = 8;
            $str = "1234567890abcdefghijklmnopqrstuvwxyz()/$";
            $randStr = substr(str_shuffle($str),0 , $KeyLength );

            // $checkKey = $this->checkKeys($randStr);

            // while ($checkKey == true){
            //     $randStr = substr(str_shuffle($str),0 , $KeyLength );
            //     $checkKey = $this->checkKeys($randStr);
            // }
                return $randStr;
        }


    //Verifirer le member

    public function MemberUserCheck($member){
        if(Auth::id() !== $member->id){
            throw new MemberNotBelongsToUser;
                }
            }

    public function getAllParents($id = NULL) {
        if ($id == NULL) {
            return false;
            }

            $results = MemberShip::where('parrain_id= $id')->get();

            $res[] = $results;
            if ($results->parrain_id != 0) {
                $results = $this->getAllParents($results->parrain_id);
                $res = array_push($res, $results);
                }
        return $res;
        }

    /* get all member details by parent id */


    /**Cette function noous permet de liste le niveau des membre  */


    public function getChildren($id )

        {
            $results = Membership::where('parrain_id', '=', $id)->get()->all();
            return $results;

            // foreach ($results as $res) {
            //     $var = str_repeat(' ', $level) . $res->parrain_id. "\n";
            //     $count += 1 + $this->children($res->parrain_id, $level + 1);
            // }

            // return $results;
        }

    // C'est le tableau genealogique que notre fonction retournera

    // id est l'identifiant de l'abonne
    // first_time est pour specifier si c'est premier fois le programme est executer
    //last_level nous donne les information sur le dernier niveau de l'arbre
    //Aspect du resultat final:
    /**
      *[
      [['parent'=> 0, 'children' => [1, 2, 3]],] //level 1
      [['parent'=> 1, 'children' => [10, 20, 30]],['parent'=> 2, 'children' => [100, 200, 300]],['parent'=> 3, 'children' => [101, 201, 301]],] //level 2
      .
      .
      .
      ]
      **/

      public function TopLevelBranchIsCompleted($last_level, $counter)
      {
          $childrenCountPass = 0 ;

          foreach ($last_level as $branch)
          {
              if(count($branch['children']) >=3)
              {
                  $childrenCountPass++;
              }
          }
          return $childrenCountPass >= 3 ** ($counter-2);
      }

    public function getTree($id, $first_time = true, $last_level = array(), $counter=0)
        {
            //C'est la variable que nous allons utiliser pour le table de chaque niveau
            $levelsChild = array();
            $counter++;
            if ($first_time)
            {
                if (!is_null($id))
                {
                    $childrens = $this->getChildren($id);
                    if (!empty($childrens))
                    {
                        array_push($levelsChild, ['parent' => $id, 'children' => $childrens]);
                        array_push($this->levels['tree'], $levelsChild);
                        return $this->getTree(null, false, $levelsChild, $counter);
                    }
                }
            }
            else
            {
                if($this->TopLevelBranchIsCompleted($last_level, $counter))
                {
                    $this->finallevel++;
                }
            }

            {
                foreach ($last_level as $branch)
                {
                        foreach ($branch['children'] as $parrain)
                        {
                            $childrens = $this->getChildren($parrain->id);
                            if (!empty($childrens))
                            {
                                array_push($levelsChild, ['parent' =>$parrain->id , 'children' => $childrens]);
                            }
                        }
                        if (!empty($levelsChild))
                        {
                            array_push($this->levels['tree'], $levelsChild);
                            return $this->getTree(null, false, $levelsChild, $counter);
                        }
                }
            }
            $this->levels['finallevel'] = $this->finallevel;

            return $this->levels;
        }

    // public function SendCode(Request $request)
    // {
    //     # code...
    //     $this->validate($request, [
    //         'phone_number' => 'required',
    //         'email' => 'required|email'
    //     ]);
    //     $data = array(
    //         'phone_number' => $request->phone_number,
    //         'email' => $request->email
    //     );
    //     Mail::to($request->member())->send(new SendCode($data));
    //     return back()->with('success', 'Thanks for your inscription! ');
    // }
}
