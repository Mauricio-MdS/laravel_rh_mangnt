<x-layout-app page-title="Human resources">
    <div class="w-100 p-4">

        <h3>Human Resources Colaborators</h3>

        <hr>

        @if ($colaborators->count() === 0)
            <div class="text-center my-5">
                <p>No colaborators found.</p>
                <a href="{{ route('colaborators.new-colaborator') }}" class="btn btn-primary">Create a new colaborator</a>
            </div>
        @else
            <div class="mb-3">
                <a href="{{ route('colaborators.new-colaborator') }}" class="btn btn-primary">Create a new colaborator</a>
            </div>

            <table class="table w-50" id="table">
                <thead class="table-dark">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Permissions</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($colaborators as $colaborator)
                        <tr>
                            <td>{{ $colaborator->name }}</td>
                            <td>{{ $colaborator->email }}</td>
                            <td>{{ implode(',', json_decode($colaborator->permissions)) }}</td>
                            <td>
                                <div class="d-flex gap-3 justify-content-end">
                                    @if ($colaborator->id === 1)
                                        <i class="fa-solid fa-lock"></i>
                                    @else
                                        <a href="#" class="btn btn-sm btn-outline-dark"><i
                                                class="fa-regular fa-pen-to-square me-2"></i>Edit</a>
                                        <a href="#" class="btn btn-sm btn-outline-dark"><i
                                                class="fa-regular fa-trash-can me-2"></i>Delete</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</x-layout-app>
