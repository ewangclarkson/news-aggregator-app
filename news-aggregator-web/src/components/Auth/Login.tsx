import React, { useState } from 'react';
import {useAuth} from "../../context/AuthContext";
import {LoginRequest} from "../../models/LoginRequest";
import icon from "../Assets/innoscripta-logo.svg";
import Loader from '../loader/Loader';

const Login: React.FC = () => {
    const guest: LoginRequest = { email: '', password: '' };
    const [user, setUser] = useState<LoginRequest>(guest);
    const checkIn = useAuth();

    const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (validateInput(user)) {
            await checkIn.login(user);
        }
        setUser(guest);
    };

    const validateInput = ({ email, password }: LoginRequest): boolean => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isEmailValid = emailRegex.test(email);
        const isPasswordValid = password.length >= 6;

        return isEmailValid && isPasswordValid;
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const name = e.target.name;
        const value = e.target.value;
        setUser({ ...user, [name]: value });
    };

    return (
        <>
            {checkIn.toggleSignIn && (
                <div className="login__block active" id="l-login">
                    <div className="login__block__header">
                        <img src={icon}/>
                        Hi there! Please Sign in
                    </div>
                    <div className="login__block__body">
                        <form onSubmit={handleSubmit}>
                            <div className="form-group form-group--left">
                                <label>Email Address</label>
                                <input
                                    type="email"
                                    className="form-control"
                                    value={user.email}
                                    name="email"
                                    onChange={handleChange}
                                />
                                <i className="form-group__bar" />
                            </div>

                            <div className="form-group form-group--left">
                                <label>Password</label>
                                <input
                                    type="password"
                                    className="form-control"
                                    value={user.password}
                                    name="password"
                                    onChange={handleChange}
                                />
                                <i className="form-group__bar" />
                            </div>
                            {checkIn.loading ? <Loader className="center" /> : null}
                            <button
                                type="submit"
                                className="btn login__block__btn"
                                disabled={checkIn.loading}
                            >
                               Login
                            </button>
                        </form>
                        <br />
                        <br />
                        <a className="dropdown-item" onClick={() => checkIn.switchCheckIn(false)}>
                            <i className="zmdi zmdi-account-add text-green" /> Create an account
                        </a>
                    </div>
                </div>
            )}
        </>
    );
};

export default Login;