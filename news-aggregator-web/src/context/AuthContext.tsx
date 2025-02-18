import React, {ReactNode, useState} from 'react';
import {useLocation, useNavigate} from 'react-router-dom';
import {SignUpRequest} from "../models/SignUpRequest";
import {LoginRequest} from "../models/LoginRequest";
import AuthService from "../services/AuthService";
import StorageService from "../services/StorageService";

interface AuthContextProps {
    error: boolean;
    success: boolean;
    alert: string;
    toggleSignIn: boolean;
    switchCheckIn: (value: boolean) => void;
    login: (loginRequest: LoginRequest) => Promise<void>;
    register: (signUpRequest: SignUpRequest) => Promise<void>;
    logout: () => Promise<void>;
    resetAlert: (state: boolean) => void;
    loading: boolean;
}

const AuthContext = React.createContext<AuthContextProps | null>(null);

interface AuthProviderProps {
    children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({children}) => {
    const [error, setError] = useState<boolean>(false);
    const [success, setSuccess] = useState<boolean>(false);
    const [loading, setLoading] = useState<boolean>(false);
    const [alert, setAlert] = useState<string>('');
    const [toggleSignIn, setToggleSignIn] = useState<boolean>(true);

    const navigate = useNavigate();
    const location = useLocation();

    const switchCheckIn = (value: boolean) => {
        setError(false);
        setSuccess(false);
        setToggleSignIn(value);
    };

    const login = async (user: LoginRequest) => {
        const from = (location.state && location.state.from && location.state.from.pathname) || '/home';
        setLoading(true);
        setError(false);
        try {
            const auth = await AuthService.login(user);
            const authData = {
                token: 'Bearer ' + auth.token,
                expiresIn: auth.expiresIn,
                refreshToken: auth.refreshToken,
                user: auth.user,
            }
            StorageService.login(authData)
            setLoading(false);
            window.location.href = '/';
        } catch (error) {
            setAlert('Invalid credentials');
            setError(true);
            setLoading(false);
        }
    };

    const register = async (user: SignUpRequest) => {
        setLoading(true);
        try {
            await AuthService.register(user);
            setLoading(false);
            setAlert('Your account was created successfully');
            setSuccess(true);
            setToggleSignIn(true);
        } catch (err) {
            setLoading(false);
            setAlert('Failed to create account! Please try again later');
            setError(true);
        }
    };

    const resetAlert = (state: boolean) => {
        setAlert('');
        setSuccess(state);
        setError(state);
    };

    const logout = async () => {
        try {
            await AuthService.logout();
            StorageService.logout();
            window.location.href = '/';
        } catch (error) {
            StorageService.logout();
            window.location.href = '/';
        }
    }

    const authContextValue = {
        error,
        success,
        alert,
        toggleSignIn,
        switchCheckIn,
        login,
        register,
        logout,
        resetAlert,
        loading
    };

    return (
        <AuthContext.Provider value={authContextValue}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = React.useContext(AuthContext);
    if (!context) {
        throw new Error('useAuthProps must be used within an AuthProvider');
    }
    return context;
};