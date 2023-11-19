<?php

namespace Database\Seeders;

use App\Enum\Roles;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        # First User
        $user = User::create([
            'name' => 'Root',
            'email' => 'root@root.com',
            'password' => Hash::make('root')
        ]);
        $user->assignRole(Role::findByName(Roles::ADMIN, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Second User
        $user = User::create([
            'name' => 'Fake',
            'email' => 'Fake1@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Third User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake2@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Fourth User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake3@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Fifth User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake4@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Sixth User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake5@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Seventh User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake6@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Eighth User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake7@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Ninth User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake8@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
        # Tenth User
        $user = User::create([
            'name' => 'Root',
            'email' => 'Fake9@gmail.com',
            'password' => Hash::make('123456')
        ]);
        $user->assignRole(Role::findByName(Roles::USER, 'api'));
        $media = new Media([
            'size' => 298124,
            'mime_type' => 'image/png',
            'url' => 'default-image/Avatar.png'
        ]);
        $media->user()->associate($user->id);
        $media->save();

        $user->media()->sync($media, [ 'create_at' => Carbon::now()]);
        $user->save();
    }
}
