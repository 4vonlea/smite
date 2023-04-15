const Sequelize = require("sequelize");
const sequelize = new Sequelize(
	process.env.DB_NAME,
	process.env.DB_USERNAME,
	process.env.DB_PASSWORD,
	{
		host: HOST,
		dialect: mysql,
		operatorsAliases: false,
		pool: {
			max: 5,
			min: 0,
			idle: 10000,
		},
	}
);
const db = {};

db.Sequelize = Sequelize;
db.sequelize = sequelize;
module.exports = db;
