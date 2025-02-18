import React from 'react';
import './Alert.css';

interface AlertProps {
    className: string;
    message: string;
    show: boolean;
    reset: (value: boolean) => void;
}

const Alert: React.FC<AlertProps> = ({ className, message, show, reset }) => {
    return (
        <>
            {show ? (
                <div className={className} role="alert">
                    {message}
                    <button type="button" className="close" onClick={() => reset(false)}>
                        <span aria-hidden="true" className="text-white">&times;</span>
                    </button>
                </div>
            ) : null}
        </>
    );
};

export default Alert;