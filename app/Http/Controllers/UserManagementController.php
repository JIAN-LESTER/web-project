<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Logs;
use App\Models\User;
use App\Models\Year;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Storage;

class UserManagementController extends Controller
{
    public function create()
    {
        $courses = Course::all();
        $years = Year::all();
        return view('admin.user_crud.add_user', compact('courses', 'years'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'nullable|in:user,admin',
            'course_id' => 'nullable|exists:courses,courseID',
            'year_id' => 'nullable|exists:years,yearID',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $avatarPath = 'avatars/default.png';

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        
        $yearID = $validated['year_id'] ?? null;
        $courseID = $validated['course_id'] ?? null;

        if($yearID == 0 || $yearID == null){
            $courseID = null;
        }
        
        $authUser = Auth::user();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'user_status' => 'active',
            'courseID' => $courseID, 
            'yearID' => $yearID, 
            'is_verified' => 1,
            'avatar' => $avatarPath,
        ]);

        
        Logs::create([
            'userID' => $authUser->userID,
            'action_type' => "Added a new user: {$validated['name']}.",
            'timestamp' => now(),
        ]);


        return redirect()->route('admin.user_management')->with('success', 'User added successfully.');
    }


    public function show(string $id)
    {

        $user = User::findOrFail(($id));
        
        return view('admin.user_crud.show_user', compact('user'));
    }

    public function edit(string $id)
    {
        // Use userID as the primary key
        $user = User::findOrFail($id);
        $years = Year::all();
        $courses = Course::all();
    
        return view('admin.user_crud.edit_user', compact('user', 'years', 'courses'));
    }
    
    public function update(Request $request, string $id)
    {
        // Use userID as the primary key
        $user = User::findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',userID',
            'password' => 'nullable|min:6',
            'role' => 'required|in:user,admin', // Making 'role' required
            'course_id' => 'nullable|exists:courses,courseID',
            'year_id' => 'nullable|exists:years,yearID',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
    
        // Update the user attributes
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $yearID = $validated['year_id'] ?? null;
        $courseID = $validated['course_id'] ?? null;
        
        // Update the password if provided
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        if($yearID == 0 || $yearID == null){
            $courseID = null;
        }
    
        // Update role, course, and year
        $user->role = $validated['role'];
        $user->courseID = $courseID;
        $user->yearID = $yearID;
    
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete the old avatar if it exists
            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }
    
            // Store the new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
    
        // Save the updated user
        $user->save();

        $authUser = Auth::user();

        Logs::create([
            'userID' => $authUser->userID,
            'action_type' => "Updated a user: {$validated['name']}.",
            'timestamp' => now(),
        ]);

    
        // Redirect with success message
        return redirect()->route('admin.user_management')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $currentUser = Auth::user();
        $userToDelete = User::findOrFail($id);

        if ($userToDelete->role === 'admin') {
            return redirect()->route('admin.user_management')->with('error', 'Admin cannot be deleted.');
        }

        if ($userToDelete->id === $currentUser->id) {
            return redirect()->route('admin.user_management')->with('error', 'You cannot delete your own account.');
        }

        $userToDelete->delete();

        Logs::create([
            'userID' => $currentUser->userID,
            'action_type' => "Deleted a user: {$userToDelete->name}.",
            'timestamp' => now(),
        ]);



        return redirect()->route('admin.user_management')->with('success', 'User has been deleted successfully.');
    }

    public function editProfile(string $id)
    {
        return view('profile.edit_profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->userID . ',userID',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'course_id' => 'nullable|exists:courses,courseID',
            'year_id' => 'nullable|exists:years,yearID',
            'old_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'The current password is incorrect.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->courseID = $validated['course_id'];
        $user->yearID = $validated['year_id'];
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        Logs::create([
            'userID' => $user->userID,
            'action_type' => "Updated own profile of user: {$user->name}.",
            'timestamp' => now(),
        ]);


        return redirect()->route('profile.edit_profile')->with('success', 'User profile updated successfully.');


    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));

    }
}
