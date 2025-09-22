<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Demo LocalStorage + Redis</title>
</head>
<body>
  <h2>Client: LocalStorage</h2>
  <input id="dataInput" placeholder="Nhập dữ liệu..." />
  <button onclick="saveLocal()">Lưu Local</button>
  <button onclick="loadLocal()">Xem Local</button>
  <button onclick="sendToServer()">Gửi lên Server (Redis)</button>

  <p id="output"></p>

  <script>
    function saveLocal() {
      const value = document.getElementById("dataInput").value;
      localStorage.setItem("myData", value);
      alert("✅ Đã lưu vào LocalStorage!");
    }

    function loadLocal() {
      const data = localStorage.getItem("myData");
      document.getElementById("output").innerText =
        "LocalStorage: " + (data || "Chưa có gì");
    }

    function sendToServer() {
      const data = localStorage.getItem("myData");
      if (!data) {
        alert("❌ Chưa có dữ liệu trong LocalStorage!");
        return;
      }

      fetch("http://localhost:8081/training-php/public/save_redis.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify({
          key: "myData",
          value: data
        })
      })
        .then(res => {
          if (!res.ok) {
            return res.text().then(text => {
              throw new Error("HTTP " + res.status + " - " + text);
            });
          }
          return res.json();
        })
        .then(result => {
          console.log("Kết quả server:", result);
          if (result.success) {
            alert("🚀 Server lưu Redis: " + result.message);
          } else {
            alert("❌ Server báo lỗi: " + result.error);
          }
        })
        .catch(err => {
          console.error("Lỗi gửi server:", err);
          alert("❌ Lỗi gửi server: " + err.message);
        });
    }
  </script>
</body>
</html>
