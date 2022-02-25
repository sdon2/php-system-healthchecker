<h5 style="color:red;font-size:medium">{{ $title }}</h5>

<table style="width:100%;border-collapse:collapse;border:1px solid black;">
    <thead>
        <tr>
            <th width="14.285714285714%" style="padding:10px 5px;text-align:center;border:1px solid black">Total</th>
            <th width="14.285714285714%" style="padding:10px 5px;text-align:center;border:1px solid black">Used</th>
            <th width="14.285714285714%" style="padding:10px 5px;text-align:center;border:1px solid black">Used %</th>
            <th width="14.285714285714%" style="padding:10px 5px;text-align:center;border:1px solid black">Free</th>
            <th width="14.285714285714%" style="padding:10px 5px;text-align:center;border:1px solid black">Free %</th>
            <th width="14.285714285714%" style="padding:10px 5px;text-align:center;border:1px solid black;color:green">Expected</th>
            <th style="padding:10px 5px;text-align:center;border:1px solid black;color:red">Utilisation</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:10px 5px;text-align:center;border:1px solid black">{{ $total }}MB</td>
            <td style="padding:10px 5px;text-align:center;border:1px solid black">{{ $used }}MB</td>
            <td style="padding:10px 5px;text-align:center;border:1px solid black">{{ $used_percent }}%</td>
            <td style="padding:10px 5px;text-align:center;border:1px solid black">{{ $free }}MB</td>
            <td style="padding:10px 5px;text-align:center;border:1px solid black">{{ $free_percent }}%</td>
            <td style="padding:10px 5px;text-align:center;border:1px solid black;color:green">{{ $expected }}%</td>
            <td style="padding:10px 5px;text-align:center;border:1px solid black;color:red">{{ number_format($utilisation, 0) }}%</td>
        </tr>
    </tbody>
</table>
