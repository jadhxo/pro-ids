import { Outlet } from "react-router-dom";
import Sidebar from "./Sidebar";
import Topbar from "./Topbar";

export default function StudentLayout() {
  return (
    <div style={{ display: "flex", minHeight: "100vh", background: "#f8fafc" }}>
      {/* LEFT SIDEBAR */}
      <Sidebar />

      {/* RIGHT CONTENT */}
      <div style={{ flex: 1, display: "flex", flexDirection: "column" }}>
        <Topbar title={''}/>

        <main style={{ padding: "32px" }}>
          <Outlet />
        </main>
      </div>
    </div>
  );
}