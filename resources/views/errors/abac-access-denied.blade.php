@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Erişim Reddedildi</h3>
                </div>

                <div class="card-body">
                    <div class="alert alert-danger">
                        <h4 class="alert-heading">Erişim Yetkiniz Yok!</h4>
                        <p>{{ session('error') ?? 'Bu sayfaya erişim yetkiniz bulunmamaktadır.' }}</p>
                        <hr>
                        <p class="mb-0">
                            ABAC yönetim paneline erişmek için aşağıdaki özniteliklere sahip olmanız gerekmektedir:
                            <ul>
                                <li>Rol: Admin veya System Administrator</li>
                                <li>Güvenlik İzni: 4 veya üzeri</li>
                                <li>Yönetici Yetkisi: Evet</li>
                            </ul>
                        </p>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary">Ana Sayfaya Dön</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 