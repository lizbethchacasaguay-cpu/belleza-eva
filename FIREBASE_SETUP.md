# Configuración de Firebase - Belleza Eva

## ✅ Estado de la Integración

La integración con Firebase Storage está completamente configurada y funcional.

## 📋 Archivos Clave

### 1. Credenciales
- **Ubicación**: `storage/app/public/firebase_credentials.json`
- **Proyecto**: `belleza-eva`
- **Bucket**: `belleza-eva-f8a73.firebasestorage.app`

### 2. Configuración del Entorno
- **Archivo**: `.env`
- **Variables**:
  ```
  FIREBASE_CREDENTIALS=storage/app/public/firebase_credentials.json
  FIREBASE_STORAGE_BUCKET=belleza-eva-f8a73.firebasestorage.app
  ```

### 3. Servicio Firebase
- **Archivo**: `app/Services/FirebaseServices.php`
- **Métodos principales**:
  - `uploadImage($file, $folder)` - Sube un archivo a Firebase
  - `deleteImage($imageUrl)` - Elimina un archivo de Firebase
  - `getPublicUrl($fileName)` - Obtiene la URL pública

## 🚀 Cómo Usar

### Subir una Imagen

```php
// En tu controlador
use App\Services\FirebaseServices;

class ProductController extends Controller
{
    protected FirebaseServices $firebaseService;

    public function __construct(FirebaseServices $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function store(Request $request)
    {
        // ...validaciones...
        
        // Subir imagen
        $url = $this->firebaseService->uploadImage(
            $request->file('image'),
            'productos' // carpeta en Firebase
        );

        // Guardar URL en base de datos
        Product::create([
            'name' => $request->name,
            'image_url' => $url
        ]);
    }
}
```

### Eliminar una Imagen

```php
// En tu controlador
public function destroy($id)
{
    $product = Product::find($id);

    // Eliminar imagen de Firebase
    if ($product->image_url) {
        $this->firebaseService->deleteImage($product->image_url);
    }

    $product->delete();
}
```

## 🔒 Seguridad

### Validaciones Implementadas

1. ✅ Validación de archivo existente
2. ✅ Validación de credenciales
3. ✅ Validación de bucket configurado
4. ✅ Manejo de excepciones con logs
5. ✅ MIME type tracking

### Logs

Todos los eventos de Firebase se registran en:
- `storage/logs/laravel.log`

**Ejemplo de log**:
```
[2025-12-14] Archivo subido exitosamente: https://storage.googleapis.com/belleza-eva-f8a73.firebasestorage.app/productos/...
```

## 🧪 Testing

Para probar la integración:

```bash
# 1. Crear un producto con imagen
POST /api/products
Content-Type: multipart/form-data

{
    "name": "Producto Test",
    "description": "Descripción",
    "price": "99.99",
    "image": [archivo]
}

# 2. Verificar que la imagen se subió a Firebase
# La respuesta debe contener una URL válida de Firebase

# 3. Actualizar producto
PUT /api/products/{id}
Content-Type: multipart/form-data

{
    "image": [nuevo archivo]
}
# Esto eliminará la imagen antigua y subirá la nueva

# 4. Eliminar producto
DELETE /api/products/{id}
# Esto eliminará tanto el producto como la imagen de Firebase
```

## 📊 Estructura de Carpetas en Firebase

```
belleza-eva-f8a73.firebasestorage.app/
├── productos/
│   ├── 1702565234_5677a89b.jpg
│   ├── 1702565235_5677a89c.png
│   └── ...
```

## ⚠️ Posibles Errores y Soluciones

### Error: "Archivo de credenciales no encontrado"
- **Causa**: El archivo `firebase_credentials.json` no existe o está en otra ubicación
- **Solución**: Verifica que el archivo esté en `storage/app/public/firebase_credentials.json`

### Error: "FIREBASE_STORAGE_BUCKET no configurado"
- **Causa**: Falta la variable de entorno en `.env`
- **Solución**: Agrega a tu `.env`:
  ```
  FIREBASE_STORAGE_BUCKET=belleza-eva-f8a73.firebasestorage.app
  ```

### Error: "Archivo inválido o no existe"
- **Causa**: El archivo enviado no es válido
- **Solución**: Verifica que:
  - El archivo sea un tipo de imagen válido (jpg, png, etc.)
  - El tamaño no exceda 2MB
  - El archivo se envíe correctamente en la solicitud

### Las imágenes no cargan en el frontend
- **Causa**: CORS no está configurado en Firebase
- **Solución**: Configura CORS en Firebase Console:
  ```bash
  gsutil cors set cors.json gs://belleza-eva-f8a73.firebasestorage.app
  ```
  Archivo `cors.json`:
  ```json
  [
    {
      "origin": ["http://localhost:*", "https://tudominio.com"],
      "method": ["GET", "HEAD"],
      "responseHeader": ["Content-Type"]
    }
  ]
  ```

## 📚 Dependencias

- `kreait/laravel-firebase: ^6.2` ✅ Instalado
- PHP ^8.2
- Laravel ^11.0

## 🔄 Próximos Pasos Recomendados

1. ✅ Configurar CORS en Firebase Console (si el frontend lo requiere)
2. ✅ Implementar caché de URLs de imágenes en BD
3. ✅ Agregar watermark o procesamiento de imágenes
4. ✅ Configurar política de retención de archivos en Firebase

---

**Última actualización**: 14 de Diciembre de 2025
**Estado**: Producción Lista ✅
