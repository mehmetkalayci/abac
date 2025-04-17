@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Politika Düzenle: {{ $policy->name }}</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('abac.policies.update', $policy) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Politika İsmi</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $policy->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Açıklama</label>
                            <textarea name="description" id="description" class="form-control" required>{{ $policy->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="effect">Etki</label>
                            <select name="effect" id="effect" class="form-control" required>
                                <option value="allow" {{ $policy->effect === 'allow' ? 'selected' : '' }}>İzin Ver</option>
                                <option value="deny" {{ $policy->effect === 'deny' ? 'selected' : '' }}>Reddet</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="resource">Kaynak</label>
                            <input type="text" name="resource" id="resource" class="form-control" value="{{ $policy->resource }}" required>
                        </div>

                        <div class="form-group">
                            <label for="action">Eylem</label>
                            <input type="text" name="action" id="action" class="form-control" value="{{ $policy->action }}" required>
                        </div>

                        <div class="form-group">
                            <label for="conditions">Koşullar (JSON)</label>
                            <textarea name="conditions" id="conditions" class="form-control" required>{{ json_encode($policy->conditions, JSON_PRETTY_PRINT) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Öznitelikler</label>
                            <div id="attributes-container">
                                @foreach($policy->attributes as $index => $policyAttribute)
                                    <div class="attribute-row mb-3">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <select name="attributes[{{ $index }}][id]" class="form-control attribute-select" required>
                                                    @foreach($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}" {{ $policyAttribute->attribute_id == $attribute->id ? 'selected' : '' }}>
                                                            {{ $attribute->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select name="attributes[{{ $index }}][operator]" class="form-control operator-select" required>
                                                    <option value="equals" {{ $policyAttribute->operator === 'equals' ? 'selected' : '' }}>Eşittir</option>
                                                    <option value="not_equals" {{ $policyAttribute->operator === 'not_equals' ? 'selected' : '' }}>Eşit Değildir</option>
                                                    <option value="greater_than" {{ $policyAttribute->operator === 'greater_than' ? 'selected' : '' }}>Büyüktür</option>
                                                    <option value="less_than" {{ $policyAttribute->operator === 'less_than' ? 'selected' : '' }}>Küçüktür</option>
                                                    <option value="greater_than_or_equal" {{ $policyAttribute->operator === 'greater_than_or_equal' ? 'selected' : '' }}>Büyük Eşittir</option>
                                                    <option value="less_than_or_equal" {{ $policyAttribute->operator === 'less_than_or_equal' ? 'selected' : '' }}>Küçük Eşittir</option>
                                                    <option value="in" {{ $policyAttribute->operator === 'in' ? 'selected' : '' }}>İçinde</option>
                                                    <option value="not_in" {{ $policyAttribute->operator === 'not_in' ? 'selected' : '' }}>İçinde Değil</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="attributes[{{ $index }}][value]" class="form-control value-input" value="{{ $policyAttribute->value }}" required>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger remove-attribute">X</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-secondary" id="add-attribute">Öznitelik Ekle</button>
                        </div>

                        <button type="submit" class="btn btn-primary">Politikayı Güncelle</button>
                        <a href="{{ route('abac.index') }}" class="btn btn-secondary">İptal</a>
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
        let attributeCount = {{ count($policy->attributes) }};

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

        // Remove button functionality for existing rows
        document.querySelectorAll('.remove-attribute').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.attribute-row').remove();
            });
        });
    });
</script>
@endpush
@endsection 