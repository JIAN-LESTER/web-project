<?php

namespace App\Http\Controllers;

use App\Models\Course;
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'in:user,admin',
            'user_status' => 'active',
            'courseID' => $validated['course_id'] ?? null, // Use the course_id from the form
            'yearID' => $validated['year_id'] ?? null, // Use the year_id from the form
            'is_verified' => 1,
            'avatar' => $avatarPath,
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
        $user = User::findOrFail($id);
        return view('admin.user_crud.edit_user', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'nullable|in:user,admin',
            'course_id' => 'nullable|exists:courses,courseID',
            'year_id' => 'nullable|exists:years,yearID',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',

        ]);

        $user->name = $validated['name'];

        $user->email = $validated['email'];
        $user->password = $validated['password'];
        $user->role = $validated['role'];
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


        return redirect()->route('admin.user_management')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        $currentUser = Auth::user();
        $userToDelete = User::findOrFail($id);

        if ($userToDelete->role === 'admin') {
            return redirect()->route('admin.user_management')->with('error', 'Admin cannot be deleted.');
        }

        // if ($userToDelete->id === $currentUser->id) {
        //     return redirect()->route('admin.user_management')->with('error', 'You cannot delete your own account.');
        // }

        $userToDelete->delete();


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


        return redirect()->route('profile.edit_profile')->with('success', 'User profile updated successfully.');


    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));

    }
}
