@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ $user->name }} - Öznitelik Yönetimi</h3>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('abac.users.attributes.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Öznitelikler</label>
                            <div id="attributes-container">
                                @foreach($attributes as $attribute)
                                    @php
                                        $userAttribute = $userAttributes->where('attribute_id', $attribute->id)->first();
                                        $value = $userAttribute ? $userAttribute->value : '';
                                    @endphp
                                    <div class="form-group mb-3">
                                        <label for="attribute_{{ $attribute->id }}">{{ $attribute->name }}</label>
                                        @if($attribute->type === 'boolean')
                                            <select name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control">
                                                <option value="1" {{ $value == '1' ? 'selected' : '' }}>Evet</option>
                                                <option value="0" {{ $value == '0' ? 'selected' : '' }}>Hayır</option>
                                            </select>
                                        @elseif($attribute->type === 'json')
                                            <textarea name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control" rows="3">{{ $value }}</textarea>
                                            <small class="form-text text-muted">JSON formatında girin (örn: ["değer1", "değer2"])</small>
                                        @else
                                            <input type="text" name="attributes[{{ $attribute->id }}]" id="attribute_{{ $attribute->id }}" class="form-control" value="{{ $value }}">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Öznitelikleri Güncelle</button>
                        <a href="{{ route('abac.index') }}" class="btn btn-secondary">Geri Dön</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 