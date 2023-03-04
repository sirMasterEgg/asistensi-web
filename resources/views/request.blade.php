<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>&lrm;</title>
    <link rel="shortcut icon" href="{{ asset('cloud.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- <link rel="stylesheet" href="{{ asset('css/dropzone.min.css') }}"> --}}

    <style>
        /* textarea:focus,
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="datetime"]:focus,
        input[type="datetime-local"]:focus,
        input[type="date"]:focus,
        input[type="month"]:focus,
        input[type="time"]:focus,
        input[type="week"]:focus,
        input[type="number"]:focus,
        input[type="email"]:focus,
        input[type="url"]:focus,
        input[type="search"]:focus,
        input[type="tel"]:focus,
        input[type="color"]:focus,
        .uneditable-input:focus {
            border-color: #198754;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6);
            outline: 0 none;
        } */
    </style>
</head>

<body>

    <div class="container-fluid vh-100">
        @if ($errors->any())
            @foreach ($errors->all() as $key => $value)
                <div class="alert alert-danger my-4" role="alert">
                    {{ $value }}
                </div>
            @endforeach
        @endif
        @if (Session::has('errorpass'))
            <div class="alert alert-danger my-4" role="alert">
                {{ Session::get('errorpass') }}
            </div>
        @endif
        @if (Session::has('errordup'))
            <div class="alert alert-warning my-4" role="alert">
                {{ Session::get('errordup') }}
            </div>
        @endif
        @if (Session::has('success'))
            <div class="alert alert-success my-4" role="alert">
                {{ Session::get('success') }}
            </div>
        @endif
        {{-- <div class="d-flex h-100 align-items-center justify-content-center">
            <form class="g-3" action="{{ route('requestToken') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-auto">
                        <label for="token" class="visually-hidden">Token</label>
                        <input type="password" class="form-control" id="token">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success mb-3">Confirm</button>
                    </div>
                </div>
            </form>
        </div> --}}
        <div class="d-flex h-100 align-items-center justify-content-center">
            <form class="g-3 w-25" action="{{ route('requestToken') }}" method="post">
                @csrf
                {{-- <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Materi</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputEmail3">
                    </div>
                </div> --}}

                <fieldset class="row mb-3">
                    <legend class="col-form-label col-sm-2 pt-0">Tipe</legend>
                    <div class="col-sm-10">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="absen-radio"
                                value="Absen" checked>
                            <label class="form-check-label" for="absen-radio">
                                Absen
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="tugas-radio"
                                value="Tugas">
                            <label class="form-check-label" for="tugas-radio">
                                Tugas
                            </label>
                        </div>
                    </div>
                </fieldset>
                <div class="row mb-3">
                    <div class="col-sm-10 offset-sm-2">
                        <select class="form-select" name="period" id="period" aria-label="Default select example">
                            <option selected disabled>Minggu/Tugas ke-</option>
                            @for ($i = 1; $i <= 18; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-10 offset-sm-2">
                        <div class="form-check">
                            <input class="form-check-input" name="closed" value="0" type="checkbox"
                                id="isClosed">
                            <label class="form-check-label" for="isClosed">
                                Closed
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputPassword3">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>



    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('js/dropzone.min.js') }}"></script> --}}
    <script src="{{ asset('js/axios.min.js') }}"></script>

    <script>
        const isClosed = document.getElementById('isClosed');
        const period = document.getElementById('period');
        const type = document.getElementsByName('type');

        function fetchClose() {
            if (!isNaN(period.value)) {
                // console.log(period.value);
                // console.log(type[0].checked ? type[0].value : type[1].value);

                axios.get('/fetch-close', {
                    params: {
                        period: period.value,
                        type: type[0].checked ? type[0].value : type[1].value
                    }
                }).then((res) => {
                    if (res.data) {
                        if (res.data.is_closed === 1) {
                            isClosed.checked = true;
                        } else {
                            isClosed.checked = false;
                        }
                    } else {
                        isClosed.checked = false;
                    }
                }).catch((err) => {
                    console.log(err);
                });
            }
        }


        period.addEventListener('change', fetchClose);
        type[0].addEventListener('change', fetchClose);
        type[1].addEventListener('change', fetchClose);
    </script>
</body>

</html>
