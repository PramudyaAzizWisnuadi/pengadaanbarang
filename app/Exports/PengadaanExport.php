<?php

namespace App\Exports;

use App\Models\PengadaanBarang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PengadaanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithEvents, WithColumnWidths, WithCustomStartCell
{
    private $pengadaans;
    private $statistics;
    private $request;

    public function __construct($pengadaans, $statistics, $request)
    {
        $this->pengadaans = $pengadaans;
        $this->statistics = $statistics;
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->pengadaans;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode Pengadaan',
            'Tanggal Pengajuan',
            'Pemohon',
            'Jabatan',
            'Departemen',
            'Alasan Pengadaan',
            'Total Estimasi',
            'Status',
            'Tanggal Approval',
            'Approved By',
            'Catatan Approval'
        ];
    }

    /**
     * @param mixed $row
     */
    public function map($row): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $row->kode_pengadaan,
            \Carbon\Carbon::parse($row->tanggal_pengajuan)->format('d/m/Y'),
            $row->nama_pemohon,
            $row->jabatan,
            $row->departemen,
            $row->alasan_pengadaan,
            $row->total_estimasi,
            ucfirst($row->status),
            $row->tanggal_approval ? \Carbon\Carbon::parse($row->tanggal_approval)->format('d/m/Y') : '-',
            $row->approvedBy ? $row->approvedBy->name : '-',
            $row->catatan_approval ?? '-'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Pengadaan';
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 15, // Kode Pengadaan
            'C' => 12, // Tanggal Pengajuan
            'D' => 20, // Pemohon
            'E' => 15, // Jabatan
            'F' => 15, // Departemen
            'G' => 30, // Alasan Pengadaan
            'H' => 18, // Total Estimasi
            'I' => 12, // Status
            'J' => 12, // Tanggal Approval
            'K' => 18, // Approved By
            'L' => 25, // Catatan Approval
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A10'; // Start data from row 10 to give space for header info
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Title
                $sheet->setCellValue('A1', 'LAPORAN PENGADAAN BARANG');
                $sheet->mergeCells('A1:L1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '000000']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E8F1FF']
                    ]
                ]);

                // Tanggal export
                $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i:s'));
                $sheet->mergeCells('A2:L2');
                $sheet->getStyle('A2')->getFont()->setBold(true);

                // Filter information
                $currentRow = 3;

                // Periode filter
                if ($this->request->filled('start_date') || $this->request->filled('end_date')) {
                    $periode = 'Periode: ';
                    if ($this->request->filled('start_date')) {
                        $periode .= \Carbon\Carbon::parse($this->request->start_date)->format('d/m/Y');
                    } else {
                        $periode .= '-';
                    }
                    $periode .= ' s/d ';
                    if ($this->request->filled('end_date')) {
                        $periode .= \Carbon\Carbon::parse($this->request->end_date)->format('d/m/Y');
                    } else {
                        $periode .= \Carbon\Carbon::now()->format('d/m/Y');
                    }
                    $sheet->setCellValue('A' . $currentRow, $periode);
                    $sheet->mergeCells('A' . $currentRow . ':L' . $currentRow);
                    $currentRow++;
                }

                // Filter status
                if ($this->request->filled('status') && $this->request->status !== 'all') {
                    $sheet->setCellValue('A' . $currentRow, 'Filter Status: ' . ucfirst($this->request->status));
                    $sheet->mergeCells('A' . $currentRow . ':L' . $currentRow);
                    $currentRow++;
                }

                // Filter departemen
                if ($this->request->filled('departemen') && $this->request->departemen !== 'all') {
                    $sheet->setCellValue('A' . $currentRow, 'Filter Departemen: ' . $this->request->departemen);
                    $sheet->mergeCells('A' . $currentRow . ':L' . $currentRow);
                    $currentRow++;
                }

                // Statistik section
                $currentRow++;
                $sheet->setCellValue('A' . $currentRow, 'STATISTIK');
                $sheet->getStyle('A' . $currentRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F0F0F0']
                    ]
                ]);
                $currentRow++;

                // Statistics data in multiple columns
                $sheet->setCellValue('A' . $currentRow, 'Total Pengadaan: ' . $this->statistics['total_pengadaan']);
                $sheet->setCellValue('D' . $currentRow, 'Draft: ' . $this->statistics['draft']);
                $sheet->setCellValue('F' . $currentRow, 'Submitted: ' . $this->statistics['submitted']);
                $sheet->setCellValue('H' . $currentRow, 'Approved: ' . $this->statistics['approved']);
                $sheet->setCellValue('J' . $currentRow, 'Rejected: ' . $this->statistics['rejected']);
                $sheet->setCellValue('L' . $currentRow, 'Completed: ' . $this->statistics['completed']);
                $currentRow++;

                // Total estimasi
                $sheet->setCellValue('A' . $currentRow, 'Total Estimasi: Rp ' . number_format($this->statistics['total_estimasi'], 0, ',', '.'));
                $sheet->mergeCells('A' . $currentRow . ':L' . $currentRow);
                $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);

                // Style header tabel (row 10)
                $headerRow = 10;
                $sheet->getStyle('A' . $headerRow . ':L' . $headerRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Get last row with data
                $lastRow = $sheet->getHighestRow();

                // Add borders to all data
                $sheet->getStyle('A10:L' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Alternate row colors for better readability
                for ($row = 11; $row <= $lastRow; $row++) {
                    if (($row - 11) % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8F9FA']
                            ]
                        ]);
                    }
                }

                // Format currency column (H = Total Estimasi) and center align some columns
                $dataStartRow = 11;
                for ($row = $dataStartRow; $row <= $lastRow; $row++) {
                    // Format currency
                    $value = $sheet->getCell('H' . $row)->getValue();
                    if (is_numeric($value)) {
                        $sheet->setCellValue('H' . $row, 'Rp ' . number_format($value, 0, ',', '.'));
                    }

                    // Center align specific columns
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // No
                    $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal
                    $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status
                    $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tanggal Approval
                }

                // Wrap text for longer content columns
                $sheet->getStyle('G:G')->getAlignment()->setWrapText(true); // Alasan Pengadaan
                $sheet->getStyle('L:L')->getAlignment()->setWrapText(true); // Catatan Approval

                // Set row height for data rows
                for ($row = 11; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(25);
                }

                // Set header row height
                $sheet->getRowDimension(10)->setRowHeight(30);
            }
        ];
    }
}
