<!DOCTYPE html>
<html lang="en">

<head>
    @extends('layouts.master')
    @section('judul')
        Property Management
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('template/plugins/datatables/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
        <script>
            $(function() {
                $("#properties").DataTable();
            });
        </script>
        {{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> --}}
        <!-- <link rel="stylesheet" type="text/css" href="./style.css" /> -->
        {{-- <script type="module" src="./index.js"></script> --}}
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet-src.js"></script>
        <script>
            let koordinat = [-6.2607, 106.7816]; // koordinat Jakarta Selatan
            let penanda;
            let peta;
            const apiKey = 'YOUR_NOMINATIM_API_KEY'; // Nominatim API Key

            function reverseGeocode(lat, lon) {
                const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}&addressdetails=1`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            document.getElementById('address').value = data.display_name;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            $('#ModalAddProperty').on('shown.bs.modal', function() {
                peta = L.map('peta-tambah-properti', {
                    center: koordinat,
                    doubleClickZoom: false,
                    zoom: 13
                });

                penanda = L.marker(koordinat).addTo(peta);
                reverseGeocode(koordinat[0], koordinat[1]);

                peta.on('mouseover', function() {
                    peta._container.style.cursor = 'default';
                });

                peta.on('dblclick', function(e) {
                    if (penanda) {
                        peta.removeLayer(penanda); // hilangkan penanda sebelumnya
                    }

                    koordinat = e.latlng;
                    const koordinatY = koordinat.lat;
                    const koordinatX = koordinat.lng;
                    koordinat = [koordinatY, koordinatX];

                    penanda = L.marker(koordinat).addTo(peta);
                    reverseGeocode(koordinatY, koordinatX);
                });

                peta.on('movestart', function() {
                    peta._container.style.cursor = 'grabbing';
                });

                peta.on('moveend', function() {
                    peta._container.style.cursor = 'default';
                });

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    maxZoom: 19
                }).addTo(peta);
            });

            $('[id^="ModalEditProperty"]').each(function() {
                const ini = $(this);

                ini.on('shown.bs.modal', function() {
                    const koordinatX = ini[0].getAttribute('data-koordinat-x');
                    const koordinatY = ini[0].getAttribute('data-koordinat-y');
                    koordinat = [koordinatY, koordinatX];

                    const petaEditProperti = ini.find('[id^="peta-edit-properti-"]')[0];

                    peta = L.map(petaEditProperti, {
                        center: koordinat,
                        doubleClickZoom: false,
                        zoom: 13
                    });

                    penanda = L.marker(koordinat).addTo(peta);

                    peta.on('mouseover', function() {
                        peta._container.style.cursor = 'default';
                    });

                    peta.on('dblclick', function(e) {
                        if (penanda) {
                            peta.removeLayer(penanda); // hilangkan penanda sebelumnya
                        }

                        koordinat = e.latlng;
                        const koordinatY = koordinat.lat;
                        const koordinatX = koordinat.lng;
                        koordinat = [koordinatY, koordinatX];

                        penanda = L.marker(koordinat).addTo(peta);
                        const url =
                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${koordinatY}&lon=${koordinatX}&addressdetails=1`;

                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data && data.display_name) {

                                    ini[0].querySelector(
                                            '.area-teks-alamat-modal-edit-properti')
                                        .value = data
                                        .display_name;
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    });

                    peta.on('movestart', function() {
                        peta._container.style.cursor = 'grabbing';
                    });

                    peta.on('moveend', function() {
                        peta._container.style.cursor = 'default';
                    });

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                        maxZoom: 19
                    }).addTo(peta);
                });
            });

            // Fungsi untuk memperbarui peta dan marker berdasarkan alamat
            async function updateMapAddress(address) {
                const geocodeUrl =
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;

                try {
                    const response = await fetch(geocodeUrl);
                    const data = await response.json();
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        koordinat = [lat, lon];

                        if (penanda) {
                            peta.removeLayer(penanda);
                        }

                        peta.setView(koordinat, 13);
                        penanda = L.marker(koordinat).addTo(peta);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            $('#formulir-tambah-properti').on('submit', async function(event) {
                event.preventDefault();


                const address = document.getElementById('address').value;
                await updateMapAddress(address);

                const ini = $(this)[0];
                const dataFormulir = new FormData(ini);
                dataFormulir.append('koordinat-x', koordinat[0]);
                dataFormulir.append('koordinat-y', koordinat[1]);

                $.ajax({
                    url: '{{ route('property.store') }}',
                    contentType: false,
                    data: dataFormulir,
                    method: 'POST',
                    processData: false,
                    success: function() {
                        Swal.fire({
                            title: 'Saved!',
                            text: 'Your changes have been saved.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error saving your changes.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        </script>
        <script>
            function confirmDelete(propertyId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('property.deleted', ':id') }}'.replace(':id', propertyId),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.error) {
                                    Swal.fire({
                                        title: 'Failed',
                                        text: response.error,
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Success',
                                        text: response.success,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(status);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Cannot delete the property because this property has a schedule with a user.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }

                })
            }
        </script>
    @endpush
</head>

<body>
    @section('content')
        <link rel="stylesheet" href="{{ asset('assets/css/management.css') }}" />
        <link href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" rel="stylesheet">
        <main class="main users chart-page" id="skip-target">
            <div class="container">

                <div class="d-flex justify-content-end mb-3">
                    <button type="button" class="btn btn-add_property" data-bs-toggle="modal"
                        data-bs-target="#ModalAddProperty">Add Property</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="properties" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Property Name</th>
                                    <th>Property Image</th>
                                    <th>Property Price</th>
                                    <th>Property Status</th>
                                    <th>Action Property</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($property as $properties)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $properties->name }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                data-bs-target="#ModalImageProperty{{ $properties->id }}"
                                                style="width:30">Lihat
                                                Foto
                                            </button>
                                        </td>
                                        <td>Rp. {{ $properties->price }}</td>
                                        <td>{{ $properties->status }}</td>
                                        <td>
                                            <button type="button" class="btn btn-detail" data-bs-toggle="modal"
                                                data-bs-target="#ModalEditProperty{{ $properties->id }}"
                                                style="width:30">Edit
                                            </button>

                                            <!-- Trigger the modal with a button -->
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmDelete('{{ $properties->id }}')">Delete</button>
                                            <form id="delete-form-{{ $properties->id }}" method="post"
                                                action="{{ route('property.deleted', $properties->id) }}"
                                                style="display: none;">
                                                @method('delete')
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Property -->
                                    <div class="modal fade" data-koordinat-x="{{ $properties->koordinat_x }}"
                                        data-koordinat-y="{{ $properties->koordinat_y }}"
                                        id="ModalEditProperty{{ $properties->id }}" tabindex="-1"
                                        aria-labelledby="ModalLabel{{ $properties->id }}" aria-hidden="true">
                                        <div class="modal-dialog" style="top:0;">
                                            <div class="modal-content">
                                                {{-- Alert Here --}}
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="modal-header">
                                                    <h5 class="modal-title fs-5"
                                                        id="staticBackdropLabel{{ $properties->id }}">
                                                        Edit
                                                        Property</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="saveForm-{{ $properties->id }}" enctype="multipart/form-data"
                                                        method="POST"
                                                        action="{{ route('property.update', $properties->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <!-- Your form content here -->
                                                        <div class="mb-3">
                                                            <label for="formFile" class="form-label">Upload Image</label>
                                                            <input type="file" style="padding:0;height: 30px;"
                                                                class="form-control border-dark" id="formFile"
                                                                name="image[]" accept="image/*" onchange="showFileName()"
                                                                multiple>
                                                            <small id="fileHelp" class="form-text text-muted">No file
                                                                chosen</small>
                                                        </div>
                                                        <script>
                                                            function showFileName() {
                                                                var input = document.getElementById('formFile');
                                                                var fileHelp = document.getElementById('fileHelp');
                                                                if (input.files.length > 0) {
                                                                    fileHelp.textContent = 'File chosen: ' + input.files[0].name;
                                                                } else {
                                                                    fileHelp.textContent = 'No file chosen';
                                                                }
                                                            }
                                                        </script>
                                                        {{-- Existing Images Section (Add this) --}}
                                                        <div class="existing-images">
                                                            <label for="preview">Preview Image</label>
                                                            @foreach ($images as $image)
                                                                @if ($properties->id == $image->property_id)
                                                                    <div class="existing-image-item">
                                                                        <img src="{{ asset('storage/images_property/' . $image->image) }}"
                                                                            alt="Existing Image" width="100"
                                                                            style="margin-bottom: 10px">
                                                                        <button type="button"
                                                                            class="btn btn-danger remove-existing-image"
                                                                            data-image-id="{{ $image->id }}"
                                                                            style="margin-left:10px">
                                                                            Remove
                                                                        </button>
                                                                        <input type="hidden" name="existing_images[]"
                                                                            value="{{ $image->id }}">
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <div class="mb-3">
                                                            <br>
                                                            <label for="name">Name Property</label>
                                                            <input type="text" class="form-control border border-dark"
                                                                placeholder="Name Property" name="name"
                                                                value="{{ $properties->name }}" required>
                                                        </div>

                                                        <label for="price">Price</label>
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text" id="basic-addon1">Rp</span>
                                                            <input type="text" class="form-control border border-dark"
                                                                placeholder="Cost" aria-describedby="basic-addon1"
                                                                name="price" value="{{ $properties->price }}" required>
                                                        </div>
                                                        <label for="status" class="form-label">Property Status</label>
                                                        <br>
                                                        <select class="form-select border border-dark mb-3"
                                                            aria-label="Status" name="status">
                                                            <option value="1">Ready</option>
                                                            <option value="2">Pending</option>
                                                            <option value="3">Sold</option>
                                                        </select>
                                                        <br>
                                                        <div class="mb-3">
                                                            <div id="peta-edit-properti-{{ $properties->id }}"></div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="address">Address</label>
                                                            <textarea class="form-control border border-dark area-teks-alamat-modal-edit-properti" rows="3" name="address" id="addressa"
                                                                required>{{ $properties->address }}</textarea>
                                                        </div>

                                                        <div id="map"></div>

                                                        <div class="mb-3">
                                                            <label for="address">Description</label>
                                                            <textarea class="form-control border border-dark" placeholder="Deskripsi" rows="3" name="description"
                                                                required>{{ $properties->description }}</textarea>
                                                        </div>

                                                        <label for="facilities">Facilities</label>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="inputPassword5"
                                                                    class="form-label">sqft</label>
                                                                <input type="number"
                                                                    class="form-control border border-dark"
                                                                    id="inputnumber" aria-describedby="inputsqft"
                                                                    name="sqft" value="{{ $properties->sqft }}"
                                                                    required>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label for="status3"
                                                                    class="form-label text-start">garage</label>
                                                                <input type="number"
                                                                    class="form-control border border-dark"
                                                                    id="inputgarage" aria-describedby="inputgarage"
                                                                    name="garage" value="{{ $properties->garage }}"
                                                                    required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="status3"
                                                                    class="form-label text-start">bed</label>
                                                                <input type="number"
                                                                    class="form-control border border-dark" id="inputbed"
                                                                    aria-describedby="inputbed" name="bed"
                                                                    value="{{ $properties->bed }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="status3"
                                                                    class="form-label text-start">bath</label>
                                                                <input type="number"
                                                                    class="form-control border border-dark" id="inputbath"
                                                                    aria-describedby="inputbath" name="bath"
                                                                    value="{{ $properties->bath }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="status3"
                                                                    class="form-label text-start">floor</label>
                                                                <input type="number"
                                                                    class="form-control border border-dark"
                                                                    id="inputfloor" aria-describedby="inputfloor"
                                                                    name="floor" value="{{ $properties->floor }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <br><br>
                                                        <button type="submit" class="btn btn-primary"
                                                            style="background-color:#021622; width: 100%">Save</button>
                                                    </form>
                                                    <script>
                                                        document.getElementById('saveForm-{{ $properties->id }}').addEventListener('submit', async function(event) {
    event.preventDefault();

    const address = document.getElementById('addressa').value;

    // Make sure updateMapAddress is defined and correctly assigns to koordinat
    await updateMapAddress(address);

    // Check if koordinat is correctly populated
    if (!koordinat || koordinat.length < 2) {
        Swal.fire({
            title: 'Error!',
            text: 'Coordinates are not properly set.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    const dataFormulir = new FormData(this);
    dataFormulir.append('koordinat-x', koordinat[0]);
    dataFormulir.append('koordinat-y', koordinat[1]);

    $.ajax({
        url: '{{ route('property.update', ['id' => $properties->id]) }}',
        contentType: false,
        data: dataFormulir,
        method: 'POST',
        processData: false,
        success: function() {
            Swal.fire({
                title: 'Saved!',
                text: 'Your changes have been saved.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                window.location.reload();
            });
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error!',
                text: 'There was an error saving your changes.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- modal lihat image --}}
                                    <div class="modal fade" id="ModalImageProperty{{ $properties->id }}" tabindex="-1"
                                        aria-labelledby="ModalLabel{{ $properties->id }}" aria-hidden="true">
                                        <div class="modal-dialog" style="top:0;">
                                            <div class="modal-content">
                                                {{-- Alert Here --}}
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                <div class="modal-header">
                                                    <h5 class="modal-title fs-5"
                                                        id="staticBackdropLabel{{ $properties->id }}">
                                                        Edit
                                                        Property</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @foreach ($images as $image)
                                                            @if ($properties->id == $image->property_id)
                                                                <div class="col-md-3">
                                                                    <div class="card text-white bg-secondary mb-3">
                                                                        <img class="d-block w-100 gallery-item"
                                                                            src="{{ asset('storage/images_property/' . $image->image) }}"
                                                                            alt="slide">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    document.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-existing-image')) {
                            if (confirm('Are you sure you want to delete this image?')) {
                                const imageId = e.target.dataset.imageId;
                                fetch(`/property/images/${imageId}`, {
                                        method: 'GET'
                                    })
                                    .then(response => {
                                        if (response.ok) {
                                            e.target.closest('.existing-image-item').remove();
                                        } else {
                                            alert('Error deleting image. Please try again.'); // Menampilkan pesan kesalahan
                                        }
                                    });
                            }
                        }
                    });
                </script>
            </div>

            <!-- Create Property -->
            <div class="modal fade" id="ModalAddProperty" tabindex="-1" aria-labelledby="ModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" style="top:0;">
                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title fs-5" id="staticBackdropLabel">Form Property</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form enctype="multipart/form-data" id="formulir-tambah-properti" method="POST"
                                action="{{ route('property.store') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload Image</label>
                                    <input type="file" style="padding:0;height: 30px;"
                                        class="form-control border-dark" id="formFile" name="images[]" accept="image/*"
                                        onchange="showFileName()" multiple>
                                    <small id="fileHelp" class="form-text text-muted">No file chosen</small>
                                </div>
                                <script>
                                    function showFileName() {
                                        var input = document.getElementById('formFile');
                                        var fileHelp = document.getElementById('fileHelp');
                                        if (input.files.length > 0) {
                                            fileHelp.textContent = 'File chosen: ' + input.files[0].name;
                                        } else {
                                            fileHelp.textContent = 'No file chosen';
                                        }
                                    }
                                </script>
                                <div class="mb-3">
                                    <label for="Name">Property Name</label>
                                    <input type="text" class="form-control border border-dark" name="name"
                                        required>
                                </div>
                                <label for="price">Property Price</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="text" class="form-control border border-dark"
                                        aria-describedby="basic-addon1" name="price" required>
                                </div>
                                <label for="status" class="form-label">Property Status</label>
                                <br>
                                <select class="form-select border border-dark mb-3" aria-label="Status" name="status">
                                    <option value="1">Ready</option>
                                    <option value="2">Pending</option>
                                    <option value="3">Sold</option>
                                </select>
                                <br>
                                <div class="mb-3">
                                    <div id="peta-tambah-properti"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="address">Property Address</label>
                                    <textarea class="form-control border border-dark" rows="3" name="address" id="address" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="description">Property Description</label>
                                    <textarea class="form-control border border-dark" rows="3" name="description" required></textarea>
                                    <br>

                                </div>
                                <label for="facilities">Property Facilities</label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="inputPassword5" class="form-label">Sqft</label>
                                        <input type="number" class="form-control border border-dark" id="inputnumber"
                                            aria-describedby="inputsqft" name="sqft" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status3" class="form-label text-start">Garage</label>
                                        <input type="number" class="form-control border border-dark" id="inputgarage"
                                            aria-describedby="inputgarage" name="garage" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status3" class="form-label text-start">Bed</label>
                                        <input type="number" class="form-control border border-dark" id="inputbed"
                                            aria-describedby="inputbed" name="bed" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status3" class="form-label text-start">Bath</label>
                                        <input type="number" class="form-control border border-dark" id="inputbath"
                                            aria-describedby="inputbath" name="bath" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="status3" class="form-label text-start">Floor</label>
                                        <input type="number" class="form-control border border-dark" id="inputfloor"
                                            aria-describedby="inputfloor" name="floor" required>
                                    </div>
                                </div>

                                <br><br>
                                <button type="submit" class="btn btn-primary"
                                    style="background-color:#021622; width: 100%">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="gallery-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <!-- <h5 class="modal-title" id="exampleModalLabel">Modal title</h5> -->
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <img src="" class="modal-img w-100" alt="modal img">
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("click", function(e) {
                    if (e.target.classList.contains("gallery-item")) {
                        const src = e.target.getAttribute("src");
                        document.querySelector(".modal-img").src = src;
                        const myModal = new bootstrap.Modal(document.getElementById('gallery-modal'));
                        myModal.show();
                    }
                })
            </script>
            <script>
                (g => {
                    var h, a, k, p = "The Google Maps JavaScript API",
                        c = "google",
                        l = "importLibrary",
                        q = "__ib__",
                        m = document,
                        b = window;
                    b = b[c] || (b[c] = {});
                    var d = b.maps || (b.maps = {}),
                        r = new Set,
                        e = new URLSearchParams,
                        u = () => h || (h = new Promise(async (f, n) => {
                            await (a = m.createElement("script"));
                            e.set("libraries", [...r] + "");
                            for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                            e.set("callback", c + ".maps." + q);
                            a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
                            d[q] = f;
                            a.onerror = () => h = n(Error(p + " could not load."));
                            a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                            m.head.append(a)
                        }));
                    d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() =>
                        d[l](f, ...n))
                })
                ({
                    key: "AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg",
                    v: "weekly"
                });
            </script>
            <script>
                let map;

                async function initMap() {
                    const {
                        Map
                    } = await google.maps.importLibrary("maps");

                    map = new Map(document.getElementById("map"), {
                        center: {
                            lat: -34.397,
                            lng: 150.644
                        },
                        zoom: 8,
                    });
                }

                initMap();
            </script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @endsection
</body>
