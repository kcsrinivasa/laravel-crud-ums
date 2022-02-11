![Laravel](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)


# Laravel CRUD for UMS

Hi All!

Here is the example focused on laravel crud application on user management system using laravel, ajax, datepicker, sweet alert and toastr.

In this example we have focused on `crud`(create, read, update, delete) application for user table. `toastr` is used to show alert messages, and `sweetalert` is used to ask the confirmation from the user to add, update and delete the record. and `datepicker` is used to select the date.

### Preview
record list
![record_list](https://github.com/kcsrinivasa/laravel-crud-ums/blob/main/output/record_list.png?raw=true)
add record
![add_record](https://github.com/kcsrinivasa/laravel-crud-ums/blob/main/output/add_record.png?raw=true)
edit record
![edit_record](https://github.com/kcsrinivasa/laravel-crud-ums/blob/main/output/edit_record.png?raw=true)
sweetalert update
![update_sweetalert](https://github.com/kcsrinivasa/laravel-crud-ums/blob/main/output/update_sweetalert.png?raw=true)
toastr success message
![success_toastr](https://github.com/kcsrinivasa/laravel-crud-ums/blob/main/output/success_toastr.png?raw=true)
sweetalert delete
![delete_sweetalert](https://github.com/kcsrinivasa/laravel-crud-ums/blob/main/output/delete_sweetalert.png?raw=true)

Here are the following steps to achive simple crud application for UMS. 

### Step 1: Install Laravel
```bash
composer create-project --prefer-dist laravel/laravel usermanagementsystem
```

### Step 2: Create controller
```bash
php artisan make:controller UserController -r -mUser
```
### Step 3: Create request for validation
```bash
php artisan make:request StoreUserRequest
```

### Step 4: Add Routes
```bash
Route::resource('/user', 'App\Http\Controllers\UserController');
```

### Step 5: Update user modal
Add below functions in app/Models/User.php

```bash
protected $fillable = [ 'name','email','password','doj','dol','stillWorking','profile', ];
    /* get experience duration */
    public function getExperienceAttribute(){
        $doj=Carbon::createFromFormat('Y-m-d H:s:i',($this->doj)?$this->doj:now());
        $dol=Carbon::createFromFormat('Y-m-d H:s:i',($this->dol)?$this->dol:now());
        return $doj->diffForHumans($dol, true, false, 2);
    }
```

### Step 6: Update user migration
Add below functions in database/migrations/..create_users_table.php

```bash
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('doj');
        $table->timestamp('dol')->nullable();
        $table->boolean('stillWorking')->default(false);
        $table->string('profile')->nullable();
        $table->timestamps();
    });
```

### Step 7: Add functions in Controller
Add below functions in app/Http/Controllers/UserController.php
```bash
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user')->withUsers(User::orderBy('id','desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $doj=$dol=$profile = null;
        $stillWorking = $request->stillWorking;
        if(!$stillWorking){$stillWorking = false;}

        if($request->doj){
            $doj = Carbon::parse($request->doj)->format('Y-m-d');
        }
        if($request->dol){
            $dol = Carbon::parse($request->dol)->format('Y-m-d');
            $stillWorking = false;
        }

        //if image uploaded
        if($request->hasFile('profile')){
            $file = $request->profile; 
            $fileOriginalName = $file->getClientOriginalName(); 
            $extension = $file->extension(); 
            $size = $file->getSize(); 
            $fileName = \Str::slug(substr($fileOriginalName,0,200)).time().'.'.$extension; 
            $filePath = 'uploads/profiles/'; 
            $file->move(public_path($filePath), $fileName); 
            $profile = $filePath.$fileName;
        }

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'doj'=>$doj,
            'dol'=>$dol,
            'stillWorking'=>$stillWorking,
            'profile'=>$profile
        ]);

        if($request->ajax()){
            return response(['message'=>'Successfully added user'],201);
        }

        session()->flash('success','Successfully added user');
        return redirect(route('user.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response(['message'=>'user details','user'=>$user],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update($user_id, StoreUserRequest $request)
    {
        $user = User::find($user_id);
        if(!$user){ return response(['error'=>'user not found'],404); }
        // dd($user);

        $doj=$dol= null;
        $profile = $user->profile;
        $stillWorking = $request->stillWorking;
        if(!$stillWorking){$stillWorking = false;}

        if($request->doj){
            $doj = Carbon::parse($request->doj)->format('Y-m-d');
        }
        if($request->dol){
            $dol = Carbon::parse($request->dol)->format('Y-m-d');
            $stillWorking = false;
        }

        //if image uploaded
        if($request->hasFile('profile')){
            // remove old profile
             if($user->profile){unlink(public_path($user->profile));}

            $file = $request->profile; 
            $fileOriginalName = $file->getClientOriginalName(); 
            $extension = $file->extension(); 
            $size = $file->getSize(); 
            $fileName = \Str::slug(substr($fileOriginalName,0,200)).time().'.'.$extension; 
            $filePath = 'uploads/profiles/'; 
            $file->move(public_path($filePath), $fileName); 
            $profile = $filePath.$fileName;
        }

        $user->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'doj'=>$doj,
            'dol'=>$dol,
            'stillWorking'=>$stillWorking,
            'profile'=>$profile
        ]);

        if($request->ajax()){
            return response(['message'=>'Successfully updated user'],200);
        }

        session()->flash('success','Successfully updated user');
        return redirect(route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        if($user->profile){unlink(public_path($user->profile));}
        $user->delete();

        session()->flash('success','Successfully deleted user');
        return redirect(route('user.index'));
    }
```

### Step 8: Create Blade file

Goto "resources/views/user.blade.php" to grab the user crud application html code

### Step 9: Update database credentials
```bash
DB_DATABASE=laravel_usermanagement
DB_USERNAME=root
DB_PASSWORD=db_password
```

### Step 10: Final run and check in browser
```bash
php artisan migrate
mv server.php index.php
cp public/.htaccess .
mkdir public/uploads
mkdir public/uploads/profiles
```
open in browser
```bash
http://localhost/laravel/usermanagementsystem
```