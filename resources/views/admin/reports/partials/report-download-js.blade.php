<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.getElementById('reportForm');
    const downloadButtons = document.querySelectorAll('.report-download-button');

    if (!reportForm || downloadButtons.length === 0) {
        return;
    }

    function getFilterParams() {
        const params = new URLSearchParams();
        const elements = reportForm.querySelectorAll('input[name], select[name], textarea[name]');

        elements.forEach(el => {
            if (!el.name || !el.value) {
                return;
            }

            const value = String(el.value).trim();
            if (value !== '') {
                params.append(el.name, value);
            }
        });

        return params.toString();
    }

    function showLoadingIndicator(message) {
        let loader = document.getElementById('loadingIndicator');

        if (!loader) {
            loader = document.createElement('div');
            loader.id = 'loadingIndicator';
            loader.style.cssText = [
                'position: fixed;',
                'top: 50%;',
                'left: 50%;',
                'transform: translate(-50%, -50%);',
                'background: white;',
                'padding: 30px 40px;',
                'border-radius: 8px;',
                'box-shadow: 0 2px 12px rgba(0,0,0,0.12);',
                'z-index: 9999;',
                'text-align: center;',
                'min-width: 240px;',
            ].join(' ');
            document.body.appendChild(loader);
        }

        loader.innerHTML = `
            <div class="spinner-border text-primary mb-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>${message}</p>
        `;
        loader.style.display = 'block';
    }

    function hideLoadingIndicator() {
        const loader = document.getElementById('loadingIndicator');
        if (loader) {
            loader.style.display = 'none';
        }
    }

    function showNotification(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.style.cssText = [
            'position: fixed;',
            'top: 20px;',
            'right: 20px;',
            'z-index: 10000;',
            'min-width: 280px;',
        ].join(' ');
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    downloadButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const url = this.dataset.downloadUrl;
            const format = this.dataset.downloadFormat;
            const filePrefix = this.dataset.filePrefix || 'Report';

            if (!url || !format) {
                return;
            }

            const filterParams = getFilterParams();
            const requestUrl = filterParams ? `${url}?${filterParams}` : url;
            showLoadingIndicator(`Generating ${format.toUpperCase()}...`);

            fetch(requestUrl)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Download failed');
                        }).catch(() => {
                            throw new Error('Download failed');
                        });
                    }
                    return response.blob();
                })
                .then(blob => {
                    const blobUrl = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = `${filePrefix}_${new Date().toISOString().slice(0, 10)}.${format === 'pdf' ? 'pdf' : 'xlsx'}`;
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                    window.URL.revokeObjectURL(blobUrl);
                    hideLoadingIndicator();
                    showNotification(`${format.toUpperCase()} downloaded successfully`, 'success');
                })
                .catch(error => {
                    console.error('Download Error:', error);
                    hideLoadingIndicator();
                    showNotification(`Error downloading ${format.toUpperCase()}: ${error.message}`, 'danger');
                });
        });
    });
});
</script>
