<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet; 
use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
use PhpOffice\PhpSpreadsheet\Style\Alignment; 
use PhpOffice\PhpSpreadsheet\Style\Border; 
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportFile extends Controller
{
    public function export(Request $request)
    {

        $headerStyleArray = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        
        $dataStyleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $type = $request->input('type');
        $name = $type === 'products' ? 'Productos' : 'Usuarios';

        $data = $this->getType($type);
        if (!is_array($data)) return $data; 

        $date = date('Y-m-d');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($this->getHeaders($data), null, 'A1');
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyleArray);

        foreach(range('A', $sheet->getHighestColumn()) as $columnID) { 
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        foreach ($data as $key => $value) { 
            $sheet->fromArray($this->getValues($value), null, 'A'.($key+2));
            $sheet->getStyle('A' . ($key + 2) . ':' . $sheet->getHighestColumn() . ($key + 2))->applyFromArray($dataStyleArray);
        }

        $writer = new Xlsx($spreadsheet);  
        $path = 'public/excel/'.$name.'-'.$date.'.xlsx';
        $writer->save(storage_path('app/'.$path));

        return response()->json([
            'message' => 'File exported successfully',
            'data' => null,
        ]);
    }
    private function getType($type)
    {
        $role_id = auth()->user()->role_id;

        if($role_id === 2) {
            if ($type === 'products') { 
                $products = Product::with('user')->where('user_id', '=', $role_id)->get(); 
                return $this->formatData($type,$products)->toArray();  
            } else { 
                return response()->json([ 
                    'message' => 'Invalid type', 
                    'data' => null, 
                ]);
            }
        } else {
            if ($type === 'products') { 
                $products = Product::with('user')->get(); 
                return $this->formatData($type,$products)->toArray(); 
            } else if ($type === 'users') { 
                $users = User::with('role')->get(); 
                return $this->formatData($type,$users)->toArray(); 
            } else { 
                return response()->json([ 
                    'message' => 'Invalid type', 'data' => null, 
                ]);
            }
        }
    }
    private function formatData($type,$data)
    {
        if ($type === 'products') {
            return $data->map(function ($product) {
                return [
                    'ID' => $product->id,
                    'NOMBRE' => $product->name,
                    'DESCRIPCIÓN' => $product->description,
                    'PRECIO' => $product->price,
                    'USUARIO' => $product->user->name,
                    'RUTA IMAGEN' => $product->image,
                    'ESTADO' => $product->active ? 'Activo' : 'Inactivo',
                ];
            });
        } else if ($type === 'users') {
            return $data->map(function ($user) {
                return [
                    'ID' => $user->id,
                    'NOMBRE' => $user->name,
                    'EMAIL' => $user->email,
                    'ROL' => $user->role->name,
                    'ESTADO' => $user->active ? 'Activo' : 'Inactivo',
                ];
            });
        }
        return null;
    }
    private function getHeaders($data) { 
        return array_keys($data[0]); 
    } 
    private function getValues($data) { 
        return array_values($data); 
    }

}
