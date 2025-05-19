<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <div class="border p-4 rounded">
                <div class="text-center">
                    <h3 class="">Masuk</h3>
                </div>
                <div class="form-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="col-12 mb-2">
                            <label for="inputEmailAddress" class="form-label">Username</label>
                            <input type="text" class="form-control" id="inputEmailAddress" name="username"
                                placeholder="Username">
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>
                        <div class="col-12 mb-3">
                            <label for="inputChoosePassword" class="form-label">Enter
                                Password</label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" class="form-control border-end-0" id="inputChoosePassword"
                                    placeholder="Masukan Password" name="password"> <a href="javascript:;"
                                    class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary"><i
                                        class="bx bxs-lock-open"></i>Masuk</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
