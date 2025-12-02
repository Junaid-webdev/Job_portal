<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class AccountController extends Controller
{
    /** 
     * Show Register Page 
     */
    public function register()
    {
        return view('front.account.register');
    }

    /**
     * Handle Registration
     */
    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:confirm_password',
            'confirm_password' => 'required'
        ], [
            'name.required' => 'Please Enter Your Fullname',
            'email.required' => 'Please Enter a valid email',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password Cannot Be Empty',
            'confirm_password.required' => 'Please Confirm Your Password',
            'password.same' => 'Passwords do not match'
        ]);

        if (!$validator->passes()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Save The User
        $user = new User();
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('success', 'You have registered successfully.');

        return response()->json([
            'status' => true,
            'errors' => []
        ]);
    }


    /**
     * Show Login Page
     */
    public function login()
    {
        return view('front.account.login');
    }

    /**
     * Authenticate User
     */
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email.required' => 'Please enter your email',
            'password.required' => 'Please enter your password'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            return redirect()->route('account.profile');
        }

        return redirect()->route('account.login')
            ->with('error', 'Either Email or Password is incorrect');
    }


    /**
     * Profile Page
     */
    public function profile()
    {
      $id = Auth::user()->id;
      $user = User::where('id', $id)->first();
    
        return view('front.account.profile',[
            'user'=> $user
        ]);
    }

    // Update Your Profile //
    public function updateProfile(Request $request){
         $id = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:6|max:20',
            'email' => 'required|email|unique:users,email,' . $id . ',id', 
        ], [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email'
        ]);

        if($validator->passes()){
            $user =  User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();

        session()->flash('success','Profile updated Successfully!');

              return response()->json([
                'status' => true,
                'errors' => [],
            ]);
            
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    // Change Your Profile picture //

    public function updateProfilePic(Request $request){
       $validator = Validator::make($request->all(),[
    'image' => 'required|mimes:jpg,jpeg,png,webp,avif|max:2048',
],[
    'mimes' => 'Only JPG, JPEG, PNG, WebP, AVIF images are allowed.',
]);

        

        $id = Auth::user()->id;
        if($validator->passes()){

            // File Uploded Method //
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'-'.time().'-'.$ext;
            $image->move(public_path('profile/'), $imageName);

            // Create a samll thumbnail //
            $sourcePath = public_path('profile/'.$imageName);
            $manager = new ImageManager(Driver::class);
            $image = $manager->read(   $sourcePath );
            $image->cover(150, 160);
            $image->toPng()->save(public_path('profile/thumb/'.$imageName));


            // Delete Old Profile Picture //

            File::delete(public_path('profile/thumb/'.Auth::user()->image));
            File::delete(public_path('profile/'.Auth::user()->image));


            // Database mai name change ho jayega //
            User::where('id',$id)->update(['image' => $imageName]);

            // Response return //

            session()->flash('success','Profile Picture Upteded Successfully!');

             return response()->json([
                'status' => true,
                'errors' => [],
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }


    // Create a Job 

    public function createJob(){

        // Category database se aayegi //
     $categories  =    Category::orderBy('name','ASC')->where('status',1)->get();

        // Job Type database se aayega //
       $jobTypes =  JobType::orderBy('name','ASC')->where('status',1)->get();

        return view('front.account.job.create',[
            'categories'  =>      $categories,
            'jobTypes' => $jobTypes, 
        ]);
    }


    // Jobs ko save karenge //

    public function saveJob(Request $request){

        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobtype' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        // isme data pass kar rhe hai //

        if( $validator->passes()){

            $job = new Job();
          

            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobtype;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();


            session()->flash('success','Your Job Added Success');

             return response()->json([
                'status' => true,
                'errors' =>  [],
             ]);

        }else{
             return response()->json([
                'status' => false,
                'errors' =>  $validator->errors()
             ]);

        }
    }
public function myJobs(){

        $jobs = Job::where('user_id',Auth::user()->id)->with('jobType')->paginate(10);
    
    return view('front.account.job.my-jobs',[
        'jobs' => $jobs,
    ]);

}

public function editJob(Request $request, $id){
        // Category database se aayegi //
     $categories  =    Category::orderBy('name','ASC')->where('status',1)->get();

        // Job Type database se aayega //
       $jobTypes =  JobType::orderBy('name','ASC')->where('status',1)->get();

       
       $job = Job::where([
        'user_id' => Auth::user()->id,
        'id' => $id,
       ])->first();

       
       if($job == null){
        abort(404);

       }

    //    dd($request->toArray());die;

    return view('front.account.job.edit',[
        'categories' =>  $categories, 
        'jobTypes' =>  $jobTypes, 
        'job' =>  $job,
    ]);
}


  public function updateJob(Request $request,$id){

        $rules = [
            'title' => 'required|min:5|max:200',
            'category' => 'required',
            'jobtype' => 'required',
            'vacancy' => 'required|integer',
            'location' => 'required|max:50',
            'description' => 'required',
            'company_name' => 'required|min:3|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);

        // isme data pass kar rhe hai //

        if( $validator->passes()){

            $job =  Job::find($id);
          

            $job->title = $request->title;
            $job->category_id = $request->category;
            $job->job_type_id = $request->jobtype;
            $job->user_id = Auth::user()->id;
            $job->vacancy = $request->vacancy;
            $job->salary = $request->salary;
            $job->location = $request->location;
            $job->description = $request->description;
            $job->benefits = $request->benefits;
            $job->responsibility = $request->responsibility;
            $job->qualifications = $request->qualifications;
            $job->keywords = $request->keywords;
            $job->experience = $request->experience;
            $job->company_name = $request->company_name;
            $job->company_location = $request->company_location;
            $job->company_website = $request->company_website;
            $job->save();


            session()->flash('success','Your Job Updateds Success');

             return response()->json([
                'status' => true,
                'errors' =>  [],
             ]);

        }else{
             return response()->json([
                'status' => false,
                'errors' =>  $validator->errors()
             ]);

        }
    }

    public function deleteJob(Request $request){
       $job =  Job::where([
            'user_id' => Auth::user()->id,
            'id' => $request->jobId
       ])->first();

       if($job == null){
        session()->flash('error','Either Job Deleted or not found');
        return response()->json([
            'status' => true 
        ]);
       }

       Job::where('id', $request->jobId)->delete();
         session()->flash('success','Job deleted Successfully!');
        return response()->json([
            'status' => true 
        ]);
    }   


}
