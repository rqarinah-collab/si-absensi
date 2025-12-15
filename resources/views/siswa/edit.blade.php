<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Siswa: ' . $siswa->nama_siswa) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('siswa.update', $siswa) }}">
                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-input-label for="nisn" :value="__('NISN')" />
                            <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn"
                                :value="old('nisn', $siswa->nisn)" required autofocus />
                            <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="nama_siswa" :value="__('Nama Siswa')" />
                            <x-text-input id="nama_siswa" class="block mt-1 w-full" type="text" name="nama_siswa"
                                :value="old('nama_siswa', $siswa->nama_siswa)" required />
                            <x-input-error :messages="$errors->get('nama_siswa')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="kelas_id" :value="__('Kelas')" />
                            <select id="kelas_id" name="kelas_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelas as $kela)
                                <option value="{{ $kela->id }}"
                                    {{ old('kelas_id', $siswa->kelas_id) == $kela->id ? 'selected' : '' }}>
                                    {{ $kela->nama_kelas }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki"
                                    {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan"
                                    {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Perbarui') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>