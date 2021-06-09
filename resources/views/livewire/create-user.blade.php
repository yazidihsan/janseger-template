<div id="form-create">
    <x-jet-form-section  :submit="$action"  class="mb-4">
        @csrf
                    @method('PUT')
        <x-slot name="title">
            {{ __('User') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Lengkapi data berikut dan submit untuk membuat data user baru') }}
        </x-slot>

        <x-slot name="form">

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="name" value="{{ __('Nama') }}" />
                <small>Nama Lengkap Akun</small>
                <x-jet-input id="name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.name" />
                <x-jet-input-error for="user.name" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.email" />
                <x-jet-input-error for="user.email" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-5">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Image
                            </label>
                            <input name="user.profile_photo_path" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="file" placeholder="User Image" wire:model.defer="user.profile_photo_path" >
                            <!-- <x-jet-input-error for="user.profile_photo_path" class="mt-2" /> -->
                        </div>

            @if ($action == "createUser")
            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <small>Minimal 8 karakter</small>
                <x-jet-input id="password" type="password" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.password" />
                <x-jet-input-error for="user.password" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="password_confirmation" value="{{ __('Konfirmasi Password') }}" />
                <small>Minimal 8 karakter</small>
                <x-jet-input id="password_confirmation" type="password" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.password_confirmation" />
                <x-jet-input-error for="user.password_confirmation" class="mt-2" />
            </div>
            @endif

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="address" value="{{ __('Alamat') }}" />
                <small>Alamat</small>
                <textarea id="address" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.address" ></textarea>
                <x-jet-input-error for="user.address" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="houseNumber" value="{{ __('House Number') }}" />
                <small>House Number Akun</small>
                <x-jet-input id="houseNumber" type="number" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.houseNumber" />
                <x-jet-input-error for="user.houseNumber" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="phoneNumber" value="{{ __('Phone Number') }}" />
                <small>Nomor Telepon Akun</small>
                <x-jet-input id="phoneNumber" type="numeric" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.phoneNumber" />
                <x-jet-input-error for="user.phoneNumber" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="city" value="{{ __('City') }}" />
                <small>Kota</small>
                <x-jet-input id="city" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="user.city" />
                <x-jet-input-error for="user.city" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-5">
                <x-jet-label for="roles" value="{{ __('Roles') }}" />
                <small>Roles</small>
                <select name="roles" class="mt-1 block w-full form-control shadow-none" id="grid-last-name" wire:model.defer="user.roles">
                    <option value="USER">USER</option>
                    <option value="ADMIN">ADMIN</option>
                </select>
                <x-jet-input-error for="user.roles" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __($button['submit_response']) }}
            </x-jet-action-message>

            <x-notify-message on="saved" type="success" :message="__($button['submit_response_notyf'])" />

            <x-jet-button>
                {{ __($button['submit_text']) }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>
</div>
