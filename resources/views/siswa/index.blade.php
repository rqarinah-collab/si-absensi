<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Kelas dan Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('status'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800"
                    role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <div class="flex justify-end mb-4">
                    <x-primary-button :href="route('siswa.create')">
                        {{ __('Tambah Siswa Baru') }}
                    </x-primary-button>
                </div>

                @forelse ($kelas as $k)
                <div class="mb-8 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <h3
                        class="text-lg font-bold p-4 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-t-lg">
                        {{ $k->nama }} (Total Siswa: {{ $k->siswa->count() }})
                    </h3>
                    <div class="p-4">
                        @if ($k->siswa->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400 italic">Belum ada siswa di kelas ini.</p>
                        @else
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Nama Siswa</th>
                                        <th scope="col" class="px-6 py-3">NISN</th>
                                        <th scope="col" class="px-6 py-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($k->siswa->sortBy('nama_siswa') as $siswa)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $siswa->nama_siswa }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $siswa->nisn ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{-- Tombol Edit Siswa (BARU DITAMBAHKAN) --}}
                                            <x-secondary-button :href="route('siswa.edit', $siswa)" class="me-2">
                                                {{ __('Edit') }}
                                            </x-secondary-button>

                                            {{-- Form Hapus Siswa (Existing) --}}
                                            <form method="POST" action="{{ route('siswa.destroy', $siswa) }}"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <x-danger-button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus siswa {{ $siswa->nama_siswa }}?')">
                                                    {{ __('Hapus') }}
                                                </x-danger-button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-gray-600 dark:text-gray-400">Belum ada data kelas yang tersedia. Mohon jalankan `php
                    artisan migrate --seed` jika belum.</p>
                @endforelse

            </div>
        </div>
    </div>
</x-app-layout>