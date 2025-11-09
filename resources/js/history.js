const dropdownButton = document.getElementById('dropdownDefaultButton');
const dropdownMenu = document.getElementById('dropdown');
const dropdownLabel = document.getElementById('dropdownLabel');
const completedPaginator = document.getElementById('completedPaginator');
const cancelledPaginator = document.getElementById('cancelledPaginator');

const urlParams = new URLSearchParams(window.location.search);

if (urlParams.has('cancelled_page')) {
    document.addEventListener('DOMContentLoaded', () => {
        filterTableByStatus('cancelled');
        dropdownLabel.textContent = 'Cancelled Sessions';
        if(cancelledPaginator.classList.contains('hidden')) cancelledPaginator.classList.toggle('hidden');
    });
} else {
    document.addEventListener('DOMContentLoaded', () => {
        const defaultSession = 'Completed Sessions';
        filterTableByStatus('completed');
        dropdownLabel.textContent = defaultSession;
        if(completedPaginator.classList.contains('hidden')) completedPaginator.classList.toggle('hidden');
    });
}

dropdownButton.addEventListener('click', (e) => {
    e.stopPropagation();
    dropdownMenu.classList.toggle('hidden');
});

window.addEventListener('click', () => {
    if(!dropdownMenu.classList.contains('hidden')){
        dropdownMenu.classList.toggle('hidden');
    }
});

function filterTableByStatus(selectedStatus)
{
    const allTableRows = document.querySelectorAll('tbody tr');

    allTableRows.forEach(row => {
        const rowStatus = row.dataset.status;

        if(rowStatus === selectedStatus) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
}

const statusOptions = document.querySelectorAll('#dropdown a[data-status]');

statusOptions.forEach(option => {
    option.addEventListener('click', (e) => {
        e.preventDefault();
        const selectedStatus = option.dataset.status;
        if(selectedStatus==='completed')
        {
            dropdownLabel.textContent = 'Completed Sessions';
            if(completedPaginator.classList.contains('hidden')) completedPaginator.classList.toggle('hidden');
            cancelledPaginator.classList.toggle('hidden');
        }
        else {
            dropdownLabel.textContent = 'Cancelled Sessions';
            cancelledPaginator.classList.toggle('hidden');
            completedPaginator.classList.toggle('hidden');
        }
        filterTableByStatus(selectedStatus);
        dropdownMenu.classList.toggle('hidden');
    });
});
