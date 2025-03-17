<table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Số Lượng Bán</th>
                        <th>Tổng Doanh Thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['total_amount'], 0, ',', '.') }} VND</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
