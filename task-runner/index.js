require("dotenv").config({ path: "../" });
const express = require("express");

const app = express();
app.use(express.json());

const PORT = process.env.PORT_TASK_RUNNER || 3000; // Port
app.listen(PORT, () => {
	console.log(`Server is running on port ${PORT}.`);
});
