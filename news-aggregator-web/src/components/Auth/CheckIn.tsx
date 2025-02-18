import React, {useEffect, useState} from 'react';
import './checkin.css';
import Login from './Login';
import Register from './Register';
import { Navigate } from 'react-router-dom';
import {useAuth} from "../../context/AuthContext";
import Alert from '../Alert/Alert';
import StorageService from "../../services/StorageService";
import AuthService from "../../services/AuthService";

const CheckIn: React.FC = () => {
    const checkIn = useAuth();
    const [isAuthenticated, setIsAuthenticated] = useState<boolean | null>(null);

    useEffect(() => {
        const checkAuthentication = async () => {
            const authStatus = await AuthService.isAuth();
            setIsAuthenticated(authStatus);
        };

        checkAuthentication();
    }, []);

    if (isAuthenticated) {
        return <Navigate to="/home" />;
    }
    return (
        <div className="login">
            <Alert
                className="alert alert-danger check-in-alert-block"
                message={checkIn.alert}
                show={checkIn.error}
                reset={checkIn.resetAlert}
            /><br />
            <Alert
                className="alert alert-success check-in-alert-block"
                message={checkIn.alert}
                show={checkIn.success}
                reset={checkIn.resetAlert}
            /><br />

            <Login />
            <Register />
        </div>
    );
};

export default CheckIn;