@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>ABAC Yönetim Paneli</h3>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Politikalar</h4>
                                    <a href="{{ route('abac.policies.create') }}" class="btn btn-primary btn-sm">Yeni Politika Ekle</a>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>İsim</th>
                                                <th>Kaynak</th>
                                                <th>Eylem</th>
                                                <th>Etki</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($policies as $policy)
                                                <tr>
                                                    <td>{{ $policy->name }}</td>
                                                    <td>{{ $policy->resource }}</td>
                                                    <td>{{ $policy->action }}</td>
                                                    <td>{{ $policy->effect }}</td>
                                                    <td>
                                                        <a href="{{ route('abac.policies.edit', $policy) }}" class="btn btn-sm btn-info">Düzenle</a>
                                                        <form action="{{ route('abac.policies.delete', $policy) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Kullanıcılar</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>İsim</th>
                                                <th>Email</th>
                                                <th>İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <a href="{{ route('abac.users.attributes', $user) }}" class="btn btn-sm btn-info">Öznitelikleri Yönet</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Erişim Kontrolü</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('abac.check-access') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user_id">Kullanıcı</label>
                                            <select name="user_id" id="user_id" class="form-control" required>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="resource">Kaynak</label>
                                            <input type="text" name="resource" id="resource" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="action">Eylem</label>
                                            <input type="text" name="action" id="action" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Erişimi Kontrol Et</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 