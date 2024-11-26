<table>
	<thead class="text-center" style="vertical-align: middle;">
		<tr>
			<td rowspan="2">OFICINA</td>
			<td rowspan="2">DNI</td>
			<td rowspan="2">NOMBRES COMPLETOS</td>
			<td rowspan="2">CARGO</td>
            <td colspan="7">DATOS DEL EQUIPO / RED</td>
        </tr>
        <tr>
            <td>IP</td>
            <td>MAC</td>
            <td>PUERTO</td>
            <td>TIPO</td>
            <td>PERTENECE A LA UGEL</td>
            <td>TIPO DE CONEXIÓN</td>
            <td>TIPO DE USO</td>
		</tr>
	</thead>
	<tbody style="vertical-align: middle;">
		@foreach ($data as $d)
		<tr>
			@foreach ($d as $e)
					<td> {{$e}} </td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>
