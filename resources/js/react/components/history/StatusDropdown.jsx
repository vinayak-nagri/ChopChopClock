import { ChevronDownIcon } from '../Icons.jsx';

const labels = {
    completed: 'Completed Sessions',
    cancelled: 'Cancelled Sessions',
};

export default function StatusDropdown({ selectedStatus, isOpen, onToggle, onSelect }) {
    return (
        <div className="relative mb-2">
            <button
                className="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none cursor-pointer hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                type="button"
                onClick={(event) => {
                    event.stopPropagation();
                    onToggle();
                }}
            >
                <span>{labels[selectedStatus]}</span>
                <ChevronDownIcon className="w-2.5 h-2.5 ms-3" />
            </button>

            {isOpen && (
                <div
                    className="z-10 mt-2 absolute bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700"
                    onClick={(event) => event.stopPropagation()}
                >
                    <ul className="pt-1 pb-1/2 text-sm text-gray-700 dark:text-gray-200 mb-2">
                        <li>
                            <button
                                type="button"
                                onClick={() => onSelect('completed')}
                                className="block w-full text-left px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            >
                                Completed Sessions
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                onClick={() => onSelect('cancelled')}
                                className="block w-full text-left px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            >
                                Cancelled Sessions
                            </button>
                        </li>
                    </ul>
                </div>
            )}
        </div>
    );
}
