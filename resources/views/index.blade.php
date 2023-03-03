<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>&lrm;</title>
    <link rel="shortcut icon" href="{{ asset('cloud.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}">

    <style>
        body {
            background: #007bff;
            background: linear-gradient(to right, #0062E6, #33AEFF);
        }

        .btn-login {
            font-size: 0.9rem;
            letter-spacing: 0.05rem;
            padding: 0.75rem 1rem;
        }

        .btn-google {
            color: white !important;
            background-color: #ea4335;
        }

        .btn-facebook {
            color: white !important;
            background-color: #3b5998;
        }
    </style>
</head>

<body>
    <div class="container">
        @if (Session::has('success'))
            <div class="alert alert-success my-4" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        @error('student_id')
            <div class="alert alert-danger my-4" role="alert">
                {{ $message }}
            </div>
        @enderror
        @error('name')
            <div class="alert alert-danger my-4" role="alert">
                {{ $message }}
            </div>
        @enderror
        @error('submission')
            <div class="alert alert-danger my-4" role="alert">
                {{ $message }}
            </div>
        @enderror
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card border-0 shadow rounded-3 my-5">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title text-center mb-5 fw-light fs-5">Kumpul Tugas + Absen</h5>
                        <form method="POST" action="{{ route('post') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" name="student_id" class="form-control" id="nrp"
                                    placeholder="220116666">
                                <label for="nrp">NRP</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control" id="nama"
                                    placeholder="John Doe">
                                <label for="nama">Nama Lengkap</label>
                            </div>

                            <div class="mb-3">
                                <label for="upload" class="form-label">File</label>
                                <input class="form-control" type="file" {{ !$kumpulFile ? 'disabled' : '' }}
                                    name="submission" id="upload">
                            </div>

                            <div class="mb-3">
                                @foreach ($task as $key => $value)
                                    <div class="form-check">
                                        <input class="form-check-input checkbox-absen" name="present[]" type="checkbox"
                                            value="{{ $value->id }}" id="{{ $value->type . '-' . $value->week }}"
                                            disabled>
                                        <label class="form-check-label" for="{{ $value->type . '-' . $value->week }}">
                                            {{ $value->type . ' week ' . $value->week }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary btn-login text-uppercase fw-bold"
                                    type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @if ($errors->any())
        @foreach ($errors->all() as $key => $value)
            {{ $value }}
        @endforeach
    @endif --}}
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script>
        const nrp = document.getElementById('nrp');
        let doneTimer = 100;
        let typingTimer;

        function done() {
            const nrpValue = nrp.value;
            const checkbox = document.querySelectorAll('.checkbox-absen');
            const nama = document.getElementById('nama');

            nama.value = '';
            nama.removeAttribute('readonly');

            checkbox.forEach((item) => {
                item.removeAttribute('checked');
                item.setAttribute('disabled', true);
            });

            axios.get('/fetch-absen', {
                params: {
                    nrp: nrpValue
                },
            }).then((response) => {
                const realData = response.data;

                nama.value = realData.nama;
                nama.setAttribute('readonly', true);

                const mappedData = realData.data.map((item) => {
                    return item.type + '-' + item.week
                });

                checkbox.forEach((item) => {
                    item.setAttribute('checked', true);

                    if (mappedData.includes(item.id)) {
                        item.setAttribute('disabled', true);
                        item.setAttribute('checked', true);
                    } else {
                        item.removeAttribute('disabled');
                        item.removeAttribute('checked');
                    }

                });

                if (!mappedData.length) {
                    checkbox.forEach((item) => {
                        item.removeAttribute('disabled');
                        item.removeAttribute('checked');
                    });
                }

            }).catch((error) => {
                //
            });
        }

        nrp.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(done, doneTimer);

        });

        nrp.addEventListener('keydown', function() {
            clearTimeout(typingTimer);
        });
    </script>
</body>

</html>
