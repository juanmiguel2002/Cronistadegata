from PIL import Image
import os

# Rutas de las carpetas
source_folder = "public/images/posts"
output_folder = "public/images/posts/resized"

# Crear la carpeta de salida si no existe
if not os.path.exists(output_folder):
    os.makedirs(output_folder)

# Definir el tamaño del resized
thumbnail_size = (512, 320)

# Recorrer los archivos en la carpeta de origen
for filename in os.listdir(source_folder):
    file_path = os.path.join(source_folder, filename)

    # Verificar si el archivo es una imagen
    if filename.lower().endswith(('.jpg', '.jpeg', '.png', '.gif', '.bmp')):
        try:
            # Abrir la imagen
            with Image.open(file_path) as img:
                # Redimensionar la imagen
                img.thumbnail(thumbnail_size)

                # Añadir el prefijo "resized_" al nombre
                base_name, ext = os.path.splitext(filename)
                output_filename = f"resized_{base_name}{ext}"

                # Guardar la imagen redimensionada
                output_path = os.path.join(output_folder, output_filename)
                img.save(output_path)

                print(f"Imagen redimensionada: {output_path}")
        except Exception as e:
            print(f"Error al procesar {file_path}: {e}")
    else:
        print(f"Archivo ignorado (no es una imagen): {filename}")

print("Proceso completado.")
