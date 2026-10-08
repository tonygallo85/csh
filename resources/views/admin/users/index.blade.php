<x-app-layout>
    <h1>Users</h1>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Is Teacher</th>
            <th>Is Admin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ( $user->is_teacher)
                        ✓
                    @endif
                </td>
                <td>
                    @if ( $user->is_admin )
                        ✓
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


</x-app-layout>
