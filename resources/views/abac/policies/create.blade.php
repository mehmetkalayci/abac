@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Yeni Politika Oluştur</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('abac.policies.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Politika İsmi</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea name="description" id="description" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="effect">Etki</label>
                            <select name="effect" id="effect" class="form-control" required>
                                <option value="allow">İzin Ver</option>
                                <option value="deny">Reddet</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="resource">Kaynak</label>
                            <input type="text" name="resource" id="resource" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="action">Eylem</label>
                            <input type="text" name="action" id="action" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="conditions">Koşullar (JSON)</label>
                            <textarea name="conditions" id="conditions" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Öznitelikler</label>
                            <div id="attributes-container">
                                <div class="attribute-row mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="attributes[0][id]" class="form-control attribute-select" required>
                                                @foreach($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="attributes[0][operator]" class="form-control operator-select" required>
                                                <option value="equals">Eşittir</option>
                                                <option value="not_equals">Eşit Değildir</option>
                                                <option value="greater_than">Büyüktür</option>
                                                <option value="less_than">Küçüktür</option>
                                                <option value="greater_than_or_equal">Büyük Eşittir</option>
                                                <option value="less_than_or_equal">Küçük Eşittir</option>
                                                <option value="in">İçinde</option>
                                                <option value="not_in">İçinde Değil</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="attributes[0][value]" class="form-control value-input" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" id="add-attribute">Öznitelik Ekle</button>
                        </div>

                        <button type="submit" class="btn btn-primary">Politika Oluştur</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('attributes-container');
        const addButton = document.getElementById('add-attribute');
        let attributeCount = 1;

        addButton.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'attribute-row mb-3';
            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <select name="attributes[${attributeCount}][id]" class="form-control attribute-select" required>
                            @foreach($attributes as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="attributes[${attributeCount}][operator]" class="form-control operator-select" required>
                            <option value="equals">Eşittir</option>
                            <option value="not_equals">Eşit Değildir</option>
                            <option value="greater_than">Büyüktür</option>
                            <option value="less_than">Küçüktür</option>
                            <option value="greater_than_or_equal">Büyük Eşittir</option>
                            <option value="less_than_or_equal">Küçük Eşittir</option>
                            <option value="in">İçinde</option>
                            <option value="not_in">İçinde Değil</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="attributes[${attributeCount}][value]" class="form-control value-input" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-attribute">X</button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            attributeCount++;

            // Remove button functionality
            newRow.querySelector('.remove-attribute').addEventListener('click', function() {
                container.removeChild(newRow);
            });
        });
    });
</script>
@endpush
@endsection 