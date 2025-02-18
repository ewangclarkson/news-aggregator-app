import React, {ReactNode, useEffect, useState} from 'react';
import { useLocation, Navigate } from 'react-router-dom';
import StorageService from "../services/StorageService";
import AuthService from "../services/AuthService";

interface CheckAuthProps {
    children: ReactNode;
}

const AuthCheck: React.FC<CheckAuthProps> = ({ children }) => {
    const location = useLocation();

    const [isAuthenticated, setIsAuthenticated] = useState<boolean | null>(true);

    useEffect(() => {
        const checkAuthentication = async () => {
            const authStatus = await AuthService.isAuth();
            setIsAuthenticated(authStatus);
        };

        checkAuthentication();
    }, []);

    if (!isAuthenticated) {
        return <Navigate to="/" state={{ from: location }} replace />;
    }


    return <>{children}</>;
};

export default AuthCheck;