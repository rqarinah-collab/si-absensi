<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Absensi Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{!! session('success') !!}</span>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                        role="alert">
                        <span class="font-medium">Kesalahan input!</span> Silakan periksa kembali formulir Anda.
                    </div>
                    @endif

                    {{-- Form ini akan diarahkan ke AbsensiController@tampilAbsensi --}}
                    <form method="POST" action="{{ route('absensi.tampil') }}"
                        class="mb-8 p-4 border rounded-lg bg-gray-50">
                        @csrf
                        <h3 class="font-semibold text-lg mb-4">{{ __('Pilih Kelas dan Tanggal') }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="kelas_id" :value="__('Kelas')" />
                                <select id="kelas_id" name="kelas_id"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                                    required>
                                    <option value="">-- Pilih Kelas --</option>
                                    {{-- Gunakan $kelas_id untuk menahan nilai terpilih setelah proses tampilAbsensi --}}
                                    @foreach ($kelas as $kela)
                                    <option value="{{ $kela->id }}"
                                        {{ old('kelas_id', $kelas_id ?? '') == $kela->id ? 'selected' : '' }}>
                                        {{ $kela->nama_kelas }}
                                    </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('kelas_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="tanggal" :value="__('Tanggal Absensi')" />
                                {{-- Gunakan $tanggal untuk menahan nilai terpilih --}}
                                <x-text-input id="tanggal" class="block mt-1 w-full" type="date" name="tanggal"
                                    :value="old('tanggal', $tanggal ?? date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
                            </div>

                            <div class="pt-1">
                                <x-primary-button>
                                    {{ __('Tampilkan Siswa') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                    @if (isset($siswas) && $siswas->isNotEmpty())
                    <h3 class="font-semibold text-xl mt-8 mb-4">
                        Absensi Kelas {{ $namaKelas }} - Tanggal
                        {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
                    </h3>

                    {{-- Form ini akan diarahkan ke AbsensiController@store --}}
                    <form method="POST" action="{{ route('absensi.store') }}">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/12">
                                            NISN</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/12">
                                            Nama Siswa</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-6/12">
                                            Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($siswas as $siswa)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $siswa->nisn }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $siswa->nama_siswa }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @php
                                            // Cek status yang sudah tersimpan untuk siswa ini
                                            $currentStatus = $absensiHariIni->get($siswa->id)->status_kehadiran ??
                                            'Hadir'; // Default ke Hadir
                                            $inputName = "kehadiran[{$siswa->id}]";
                                            @endphp

                                            <div class="flex justify-center space-x-6">
                                                {{-- Hadir --}}
                                                <label class="inline-flex items-center text-green-600">
                                                    <input type="radio"
                                                        class="form-radio border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                                        name="{{ $inputName }}" value="Hadir"
                                                        {{ $currentStatus == 'Hadir' ? 'checked' : '' }} required>
                                                    <span class="ml-2">Hadir</span>
                                                </label>

                                                {{-- Izin --}}
                                                <label class="inline-flex items-center text-yellow-600">
                                                    <input type="radio"
                                                        class="form-radio border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500"
                                                        name="{{ $inputName }}" value="Izin"
                                                        {{ $currentStatus == 'Izin' ? 'checked' : '' }} required>
                                                    <span class="ml-2">Izin</span>
                                                </label>

                                                {{-- Alpa --}}
                                                <label class="inline-flex items-center text-red-600">
                                                    <input type="radio"
                                                        class="form-radio border-gray-300 text-red-600 shadow-sm focus:ring-red-500"
                                                        name="{{ $inputName }}" value="Alpa"
                                                        {{ $currentStatus == 'Alpa' ? 'checked' : '' }} required>
                                                    <span class="ml-2">Alpa</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end mt-6">
                            <x-primary-button>
                                {{ __('Simpan Absensi') }}
                            </x-primary-button>
                        </div>
                    </form>
                    @elseif (isset($siswas) && $siswas->isEmpty())
                    <div class="p-4 mt-8 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded relative"
                        role="alert">
                        Tidak ada data siswa ditemukan untuk kelas tersebut. Silakan isi data siswa terlebih dahulu di
                        menu Manajemen Siswa.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>