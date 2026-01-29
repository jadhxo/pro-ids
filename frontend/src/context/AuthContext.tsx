import { createContext, useContext, useState } from "react";
import type { ReactNode } from "react";

export type Role = "student" | "instructor";

type AuthContextType = {
  isAuthenticated: boolean;
  role: Role | null;
  loginAsStudent: () => void;
  loginAsInstructor: () => void;
  logout: () => void;
};

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [role, setRole] = useState<Role | null>(null);

  const loginAsStudent = () => {
    setIsAuthenticated(true);
    setRole("student");
  };

  const loginAsInstructor = () => {
    setIsAuthenticated(true);
    setRole("instructor");
  };

  const logout = () => {
    setIsAuthenticated(false);
    setRole(null);
  };

  return (
    <AuthContext.Provider
      value={{
        isAuthenticated,
        role,
        loginAsStudent,
        loginAsInstructor,
        logout,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) {
    throw new Error("useAuth must be used within AuthProvider");
  }
  return ctx;
}
