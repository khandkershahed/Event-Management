<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\AdminProfileUpdateRequest;

class AdminProfileController extends Controller
{
    
    public function edit(): View
    {
        return view('admin.pages.profile.edit', [
            'user' => Auth::guard('admin')->user(),
            'roles' => Role::get(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(AdminProfileUpdateRequest $request): RedirectResponse
    {


        $files = [
            'photo' => $request->file('photo'),
        ];
        $uploadedFiles = [];
        foreach ($files as $key => $file) {
            if (!empty($file)) {
                $filePath = 'admin/' . $key;
                $oldFile = $client->$key ?? null;

                if ($oldFile) {
                    Storage::delete("public/" . $oldFile);
                }
                $uploadedFiles[$key] = customUpload($file, $filePath);
                if ($uploadedFiles[$key]['status'] === 0) {
                    return redirect()->back()->with('error', $uploadedFiles[$key]['error_message']);
                }
            } else {
                $uploadedFiles[$key] = ['status' => 0];
            }
        }
        $request->user()->update([
            'name'        => $request->name ? $request->name  : $request->user()->name,
            'email'       => $request->email ? $request->email: $request->user()->email,
            'username'    => $request->username,
            'designation' => $request->designation,
            'photo'       => $uploadedFiles['photo']['status'] == 1 ? $uploadedFiles['photo']['file_path'] : $request->user()->photo,
            'password'    => $request->password ? Hash::make($request->password) : $request->user()->password,
        ]);

        Session::flash('success','Profile Updated.');
        return redirect()->back();
    }

    /**
     * Delete the user's account.
     */

    public function destroy(Request $request)
    {
            // $request->validateWithBag('userDeletion', [
            //     'password' => ['required', 'current_password'],
            // ]);

        // Get the authenticated admin user
        $user = $request->user();

        Auth::guard('admin')->logout();

        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // return Redirect::to('/');
        // return redirect('/');
    }
}
