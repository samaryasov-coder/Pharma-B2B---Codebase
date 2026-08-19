(function($){

    class FileUtils {
        static formatFileSize(bytes) {
            if (!bytes || bytes <= 0) return '0 B';
            const k = 1024;
            const sizes = ['Б', 'КБ', 'МБ', 'ГБ', 'TБ'];
            const i = Math.min(
                Math.floor(Math.log(bytes) / Math.log(k)),
                sizes.length - 1
            );
            const value = bytes / Math.pow(k, i);

            return `${value.toFixed(i === 0 ? 0 : 1)} ${sizes[i]}`;
        }

        static isDocFile(file) {
            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            const allowedExt = ['.pdf', '.doc', '.docx'];
            const name = (file?.name || '').toLowerCase();

            return (
                allowedTypes.includes(file.type) ||
                allowedExt.some(ext => name.endsWith(ext))
            );
        }
    }

    $.FileUtils = FileUtils;

})(jQuery);