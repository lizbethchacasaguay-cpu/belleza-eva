<?php

namespace App\Services;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FirebaseServices
{
    /**
     * Sube un archivo y devuelve la URL pública.
     * Usa almacenamiento local como principal para mayor confiabilidad.
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string URL pública del archivo
     * @throws \Exception
     */
    public function uploadImage($file, $folder = 'productos')
    {
        try {
            // Validar que el archivo existe y es válido
            if (!$file || !$file->isValid()) {
                throw new \Exception("Archivo inválido o no existe");
            }

            // Generar nombre único para el archivo
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $folderPath = $folder;

            // Guardar en storage/app/public (almacenamiento local)
            $path = Storage::disk('public')->putFileAs(
                $folderPath,
                $file,
                $fileName
            );

            if (!$path) {
                throw new \Exception("No se pudo guardar el archivo");
            }

            // Generar URL pública
            $publicUrl = "/storage/" . $path;
            
            Log::info("Archivo guardado localmente: {$publicUrl}");
            return $publicUrl;

        } catch (Throwable $e) {
            Log::error('Error al subir archivo: ' . $e->getMessage());
            throw new \Exception("Error al subir archivo: " . $e->getMessage());
        }
    }

    /**
     * Elimina un archivo del almacenamiento.
     * 
     * @param string $fileUrl URL del archivo a eliminar
     * @return bool true si se eliminó correctamente
     * @throws \Exception
     */
    public function deleteImage($fileUrl)
    {
        try {
            // Validar URL
            if (!$fileUrl) {
                Log::warning("URL de imagen vacía para eliminar");
                return false;
            }

            // Extraer path relativo de la URL
            // URL: /storage/productos/filename.jpg
            if (strpos($fileUrl, '/storage/') === 0) {
                $filePath = str_replace('/storage/', '', $fileUrl);
                Storage::disk('public')->delete($filePath);
                Log::info("Archivo eliminado: {$fileUrl}");
                return true;
            }

            return false;

        } catch (Throwable $e) {
            Log::error('Error al eliminar archivo: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene la URL pública de un archivo.
     * 
     * @param string $fileName Nombre/ruta del archivo
     * @return string URL pública
     */
    public function getPublicUrl($fileName)
    {
        return "/storage/" . $fileName;
    }
}
