<?php

namespace App\Services\Empresa;

use App\DTO\empresa\EmpresaDTO;
use App\Models\Cuenta;
use App\Models\Empresa;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmpresaService
{
    public function userExistsInEmpresa(string $user, int $empresa_id)
    {
        return DB::table('users as u')
            ->join('empresas as e', 'e.cuenta_id', '=', 'u.cuenta_id')
            ->where('u.usuario', $user)
            ->where('e.id', $empresa_id)
            ->exists();
    }

    public function validateUniqueFields(array $request, int $empresa_id)
    {
        if (Empresa::where('nit', trim($request['nit']))->exists() && $empresa_id == 0) {
            return response()->json(['status' => 'error', 'message' => 'El NIT proporcionado ya está registrado.']);
        }

        if (Empresa::where('nrc', trim($request['nrc']))->exists() && $empresa_id == 0) {
            return response()->json(['status' => 'error', 'message' => 'El NRC proporcionado ya está registrado.']);
        }

        return null;
    }

    public function handleCertificate(array $request, int $empresa_id): array
    {
        $file = request()->file('file_cert');
        $messageFile = '';
        $path_file = '';

        $config_DTE = Empresa::find($empresa_id);

        if (!$file) {
            return [
                ($config_DTE && $config_DTE['certificado_path'] !== "") ? basename($config_DTE['certificado_path']) : '',
                ''
            ];
        }

        $nit = trim($request['nit']);
        $extension = $file->getClientOriginalExtension();

        if ($extension !== "crt") {
            return ['', 'El formato de archivo no es compatible.'];
        }

        $destinationPath = '/var/www/docker/temp';
        $fileName = str_replace('-', '', $nit) . "." . $extension;

        // eliminar certificado viejo si existe
        if ($config_DTE && $config_DTE['certificado_path'] != '') {
            $old_name = basename($config_DTE['certificado_path']);
            $fileToDelete = $destinationPath . '/' . $old_name;

            if (file_exists($fileToDelete)) unlink($fileToDelete);
        }

        if ($file->move($destinationPath, $fileName)) {
            $path_file = $destinationPath . "/" . $fileName;
            $messageFile = 'El certificado se ha cargado exitosamente.';
        } else {
            $messageFile = 'Error al subir el certificado.';
        }
        return [$path_file, $messageFile];
    }
    /* 
    Return [boolean , path, message]
    */
    public function handleLogo(array $request, int $empresa_id): ?array
    {
        $empresa = Empresa::find($empresa_id);
        if (!request()->has('logo')) return [true, $empresa->logo,''];

        $logo = request()->file('logo');
        $extension = strtolower($logo->getClientOriginalExtension());
        $maxSize = 2 * 1024 * 1024;
        $permitidas = ['jpeg', 'jpg', 'png', 'gif', 'svg'];

        if (!in_array($extension, $permitidas)) {
            return [false,'','Solo se permiten archivos de imagen.'];
        }

        if ($logo->getSize() > $maxSize) {
            return [false,'','El archivo no debe superar los 2MB.'];
        }

        if ($empresa && $empresa->logo && Storage::exists($empresa->logo)) {
            Storage::delete($empresa->logo);
        }

        $fileName = str_replace(' ', '_', strtolower($request['nombre'])) . "." . $extension;
        $path = $logo->storeAs('logos_empresa', $fileName, 'public');
        return [true, $path, 'File cargodo con éxito.'];
    }

    //create empresa
    public function create(EmpresaDTO $datos)
    {
        $limite = Cuenta::where('id', $datos->cuenta_id)->value('limite_empresas');
        $cantidad = Empresa::where('cuenta_id', $datos->cuenta_id)->count();

        if ($cantidad >= $limite) {
            return [false, 'Ha alcanzado el límite de empresas permitidas para su cuenta.'];
        }

        $result = Empresa::create($datos->toArray());

        return [$result, 'La información de la empresa ha sido registrada con éxito.'];
    }
    //update empresa
    public function update(EmpresaDTO $datos, int $empresa_id, int $cuenta_id)
    {
        $updateData = array_filter($datos->toArray(), function($v) {
            return $v !== null;
        });
        $result = Empresa::where('id', $empresa_id)->where('cuenta_id',$cuenta_id)->update($updateData);
        return [$result, 'Los datos de la empresa se han actualizado de manera exitosa.'];
    }
}
