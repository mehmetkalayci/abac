@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Erişim Kontrolü Sonucu</h3>
                </div>

                <div class="card-body">
                    <div class="alert alert-{{ $result['allowed'] ? 'success' : 'danger' }}">
                        <h4 class="alert-heading">
                            {{ $result['allowed'] ? 'Erişim İzni Verildi' : 'Erişim Reddedildi' }}
                        </h4>
                        @if($result['policy'])
                            <p>
                                <strong>Uygulanan Politika:</strong> {{ $result['policy']->name }}<br>
                                <strong>Etki:</strong> {{ $result['policy']->effect }}<br>
                                <strong>Kaynak:</strong> {{ $result['policy']->resource }}<br>
                                <strong>Eylem:</strong> {{ $result['policy']->action }}
                            </p>
                        @else
                            <p>Bu işlem için uygun bir politika bulunamadı.</p>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Kullanıcı Bilgileri</h4>
                                </div>
                                <div class="card-body">
                                    <p><strong>İsim:</strong> {{ $user->name }}</p>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <h5>Öznitelikler:</h5>
                                    <ul>
                                        @foreach($user->userAttributes as $userAttribute)
                                            <li>
                                                <strong>{{ $userAttribute->attribute->name }}:</strong>
                                                {{ $userAttribute->value }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>İlgili Politikalar</h4>
                                </div>
                                <div class="card-body">
                                    @foreach($policies as $policy)
                                        <div class="mb-3">
                                            <h5>{{ $policy->name }}</h5>
                                            <p><strong>Etki:</strong> {{ $policy->effect }}</p>
                                            <p><strong>Koşullar:</strong></p>
                                            <ul>
                                                @foreach($policy->attributes as $policyAttribute)
                                                    <li>
                                                        {{ $policyAttribute->attribute->name }}
                                                        {{ $policyAttribute->operator }}
                                                        {{ $policyAttribute->value }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('abac.index') }}" class="btn btn-primary">Ana Sayfaya Dön</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 