(() => {
    'use strict';

    Sortable.create(document.getElementById('taskbar-buttons'), {
        animation: 350,
        forceFallback: false,
        setData: (dataTransfer, dragEl) => {
            dataTransfer.setDragImage(new Image(), 0, 0);
            dataTransfer.setData('Text', dragEl.textContent);
        }
    });
})();