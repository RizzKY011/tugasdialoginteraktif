<x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Mahasiswa</h3>
                    <button onclick="openAddModal()"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Tambah Mahasiswa
                    </button>
                </div>

                <!-- Table Mahasiswa -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-6 py-3 text-center">No</th>
                                <th class="px-6 py-3 text-center">Nama</th>
                                <th class="px-6 py-3 text-center">NIM</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100 text-center">
                            @forelse ($mahasiswa as $data)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-3 text-center text-gray-700">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-3 text-gray-700">{{ $data->name }}</td>
                                    <td class="px-6 py-3 text-gray-700">{{ $data->nim }}</td>
                                    <td class="px-6 py-3 text-center space-x-2">
                                        <button
                                            onclick="openEditModal({{ $data->id }}, '{{ $data->name }}', '{{ $data->nim }}')"
                                            class="inline-block px-4 py-2 bg-yellow-400 text-white text-xs font-medium rounded hover:bg-yellow-500 transition">
                                            Edit
                                        </button>
                                        <form action="{{ route('mahasiswa.destroy', $data->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="inline-block px-4 py-2 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition"
                                                type="submit">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="addModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Tambah Mahasiswa</h2>
            <form action="{{ route('mahasiswa.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama" required
                    class="w-full mb-4 px-3 py-2 border rounded focus:ring focus:ring-blue-300">
                <input type="text" name="nim" placeholder="NIM" required
                    class="w-full mb-4 px-3 py-2 border rounded focus:ring focus:ring-blue-300">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Edit Mahasiswa</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="text" id="editName" name="name" required
                    class="w-full mb-4 px-3 py-2 border rounded focus:ring focus:ring-blue-300">
                <input type="text" id="editNim" name="nim" required
                    class="w-full mb-4 px-3 py-2 border rounded focus:ring focus:ring-blue-300">
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif


    <!-- Script -->
    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }
    
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
    
        function openEditModal(id, name, nim) {
            document.getElementById('editName').value = name;
            document.getElementById('editNim').value = nim;
            const form = document.getElementById('editForm');
            form.action = `/mahasiswa/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }
    
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    
        // Konfirmasi Hapus
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
    
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data mahasiswa akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); 
                        }
                    });
                });
            });
        });

        const editForm = document.getElementById('editForm');
    editForm.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Simpan perubahan?',
            text: "Apakah data yang Anda masukkan sudah benar?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Ya, simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                editForm.submit(); // Submit form jika dikonfirmasi
            }
        });
    });
    </script>
    
</x-app-layout>
