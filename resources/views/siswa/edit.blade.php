<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Siswa: ') . $siswa->nama_siswa }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('siswa.update', $siswa) }}">
                        @csrf
                        @method('PUT') {{-- PENTING: Gunakan method PUT untuk update --}}

                        <div class="mb-4">
                            <x-input-label for="kelas_id" :value="__('Kelas')" />
                            <select id="kelas_id" name="kelas_id" required
                                class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="" disabled>{{ __('Pilih Kelas') }}</option>
                                @foreach ($kelas as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="nama_siswa" :value="__('Nama Siswa')" />
                            <x-text-input id="nama_siswa" class="block mt-1 w-full" type="text" name="nama_siswa"
                                :value="old('nama_siswa', $siswa->nama_siswa)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_siswa')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="nisn" :value="__('NISN (Nomor Induk Siswa Nasional)')" />
                            <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn"
                                :value="old('nisn', $siswa->nisn)" />
                            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <x-secondary-button href="{{ route('siswa.index') }}" class="me-4">
                                {{ __('Batal') }}
                            </x-secondary-button>

                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>