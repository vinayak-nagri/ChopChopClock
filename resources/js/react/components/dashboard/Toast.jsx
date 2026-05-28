import { CheckIcon } from '../Icons.jsx';

export default function Toast({ visible, message }) {
    if (!visible) {
        return null;
    }

    return (
        <div className="flex items-center w-full max-w-xs p-4 mb-4 mt-2 text-gray-500 fixed top-4 right-4 flex-col gap-2 z-[9999] bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
            <div className="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                <CheckIcon className="w-5 h-5" />
                <span className="sr-only">Check icon</span>
            </div>
            <div className="ms-3 text-sm font-normal">{message}</div>
        </div>
    );
}
