<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Throwable;
use Illuminate\Support\Facades\Log;

class FirebaseServices
{
    protected $storage;
    protected $bucketName;

    public function __construct()
    {
        try {
            // Obtener ruta correcta del archivo de credenciales
            // Usar base_path para acceder desde la raíz del proyecto
            $credentialsPath = base_path('storage/app/public/firebase_credentials.json');
            
            // Validar que el archivo existe
            if (!file_exists($credentialsPath)) {
                throw new \Exception("Archivo de credenciales no encontrado: {$credentialsPath}");
            }

            // Crear conexión a Firebase
            $factory = (new Factory)
                ->withServiceAccount($credentialsPath);

            // Inicializar servicio de Storage
            $this->storage = $factory->createStorage();
            $this->bucketName = env('FIREBASE_STORAGE_BUCKET');

            if (!$this->bucketName) {
                throw new \Exception("FIREBASE_STORAGE_BUCKET no configurado en .env");
            }
        } catch (Throwable $e) {
            Log::error('Error al inicializar Firebase: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sube un archivo a Firebase y devuelve la URL pública.
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
            $fileName = $folder . '/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Obtener bucket
            $bucket = $this->storage->getBucket($this->bucketName);

            // Subir archivo a Firebase Storage
            $object = $bucket->upload(
                fopen($file->getPathname(), 'r'),
                [
                    'name' => $fileName,
                    'metadata' => [
                        'contentType' => $file->getMimeType()
                    ]
                ]
            );

            // Hacer el archivo público
            $object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);

            // Retornar URL pública
            $publicUrl = "https://storage.googleapis.com/{$this->bucketName}/{$fileName}";
            
            Log::info("Archivo subido exitosamente: {$publicUrl}");
            return $publicUrl;

        } catch (Throwable $e) {
            Log::error('Error al subir archivo a Firebase: ' . $e->getMessage());
            throw new \Exception("Error al subir archivo: " . $e->getMessage());
        }
    }

    /**
     * Elimina un archivo de Firebase Storage.
     * 
     * @param string $imageUrl URL pública del archivo
     * @return bool true si se eliminó correctamente
     * @throws \Exception
     */
    public function deleteImage($imageUrl)
    {
        try {
            // Validar URL
            if (!$imageUrl) {
                Log::warning("URL de imagen vacía para eliminar");
                return false;
            }

            // Extraer nombre del archivo desde la URL
            // URL: https://storage.googleapis.com/{bucket}/{path}
            $bucketUrl = "https://storage.googleapis.com/{$this->bucketName}/";
            
            if (strpos($imageUrl, $bucketUrl) !== 0) {
                Log::warning("URL de imagen no pertenece al bucket: {$imageUrl}");
                return false;
            }

            // Obtener path relativo
            $fileName = str_replace($bucketUrl, '', $imageUrl);

            // Obtener bucket y eliminar objeto
            $bucket = $this->storage->getBucket($this->bucketName);
            $bucket->object($fileName)->delete();

            Log::info("Archivo eliminado exitosamente: {$fileName}");
            return true;

        } catch (Throwable $e) {
            Log::error('Error al eliminar archivo de Firebase: ' . $e->getMessage());
            // No lanzar excepción para que la eliminación del producto continúe
            return false;
        }
    }

    /**
     * Obtiene la URL pública de un archivo en Firebase.
     * 
     * @param string $fileName Nombre/ruta del archivo
     * @return string URL pública
     */
    public function getPublicUrl($fileName)
    {
        return "https://storage.googleapis.com/{$this->bucketName}/{$fileName}";
    }
}