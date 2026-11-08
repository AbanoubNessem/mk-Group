/**
 * Image Compression Utility
 * Compresses images on client side before upload to reduce file size
 */

class ImageCompressor {
    constructor(options = {}) {
        this.maxWidth = options.maxWidth || 1920;
        this.maxHeight = options.maxHeight || 1920;
        this.quality = options.quality || 0.85; // 85% quality
        this.outputFormat = options.outputFormat || 'image/jpeg';
    }

    /**
     * Compress a single image file
     * @param {File} file - The image file to compress
     * @returns {Promise<File>} - Compressed image file
     */
    async compress(file) {
        return new Promise((resolve, reject) => {
            // Check if file is an image
            if (!file.type.match(/^image\/(jpeg|jpg|png|gif|webp)$/i)) {
                resolve(file); // Return original if not an image
                return;
            }

            const reader = new FileReader();
            
            reader.onload = (e) => {
                const img = new Image();
                
                img.onload = () => {
                    // Calculate new dimensions
                    let width = img.width;
                    let height = img.height;
                    
                    if (width > this.maxWidth || height > this.maxHeight) {
                        const ratio = Math.min(
                            this.maxWidth / width,
                            this.maxHeight / height
                        );
                        width = width * ratio;
                        height = height * ratio;
                    }
                    
                    // Create canvas
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    
                    // Draw and compress
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    // Convert to blob
                    canvas.toBlob(
                        (blob) => {
                            if (!blob) {
                                resolve(file); // Fallback to original
                                return;
                            }
                            
                            // Create new file from blob
                            const compressedFile = new File(
                                [blob],
                                file.name.replace(/\.[^/.]+$/, '.jpg'), // Change extension to .jpg
                                {
                                    type: this.outputFormat,
                                    lastModified: Date.now()
                                }
                            );
                            
                            // Only use compressed if it's smaller
                            if (compressedFile.size < file.size) {
                                console.log(`Image compressed: ${(file.size / 1024 / 1024).toFixed(2)}MB -> ${(compressedFile.size / 1024 / 1024).toFixed(2)}MB`);
                                resolve(compressedFile);
                            } else {
                                console.log('Compressed image is larger, using original');
                                resolve(file);
                            }
                        },
                        this.outputFormat,
                        this.quality
                    );
                };
                
                img.onerror = () => {
                    console.error('Error loading image');
                    resolve(file); // Fallback to original
                };
                
                img.src = e.target.result;
            };
            
            reader.onerror = () => {
                console.error('Error reading file');
                resolve(file); // Fallback to original
            };
            
            reader.readAsDataURL(file);
        });
    }

    /**
     * Compress multiple image files
     * @param {FileList|Array} files - Array of image files
     * @returns {Promise<Array<File>>} - Array of compressed files
     */
    async compressMultiple(files) {
        const fileArray = Array.from(files);
        const compressedFiles = await Promise.all(
            fileArray.map(file => this.compress(file))
        );
        return compressedFiles;
    }
}

// Auto-compress images on form submit
document.addEventListener('DOMContentLoaded', function() {
    // Find all forms with file inputs in dashboard
    const forms = document.querySelectorAll('form[enctype="multipart/form-data"]');
    
    forms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            const fileInputs = form.querySelectorAll('input[type="file"][accept*="image"]');
            
            if (fileInputs.length === 0) {
                return; // No image inputs, proceed normally
            }
            
            // Check if any files are selected
            let hasFiles = false;
            fileInputs.forEach(input => {
                if (input.files && input.files.length > 0) {
                    hasFiles = true;
                }
            });
            
            if (!hasFiles) {
                return; // No files to compress, proceed normally
            }
            
            // Prevent default submission
            e.preventDefault();
            
            // Show loading indicator
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton ? submitButton.textContent : '';
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'جاري ضغط الصور...';
            }
            
            try {
                const compressor = new ImageCompressor({
                    maxWidth: 1920,
                    maxHeight: 1920,
                    quality: 0.85
                });
                
                // Process all file inputs
                for (const input of fileInputs) {
                    if (input.files && input.files.length > 0) {
                        if (input.hasAttribute('multiple')) {
                            // Multiple files
                            const compressedFiles = await compressor.compressMultiple(input.files);
                            
                            // Create new DataTransfer to replace files
                            const dataTransfer = new DataTransfer();
                            compressedFiles.forEach(file => {
                                dataTransfer.items.add(file);
                            });
                            input.files = dataTransfer.files;
                        } else {
                            // Single file
                            const compressedFile = await compressor.compress(input.files[0]);
                            
                            // Create new DataTransfer to replace file
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(compressedFile);
                            input.files = dataTransfer.files;
                        }
                    }
                }
                
                // Update button text
                if (submitButton) {
                    submitButton.textContent = 'جاري الرفع...';
                }
                
                // Submit form with compressed images
                form.submit();
                
            } catch (error) {
                console.error('Error compressing images:', error);
                alert('حدث خطأ أثناء ضغط الصور. سيتم الرفع بالصور الأصلية.');
                
                // Restore button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
                
                // Submit with original files
                form.submit();
            }
        });
    });
});

