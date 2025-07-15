<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class TaskSearch extends Task
{
    public $created_from;
    public $created_to;
    public $date_range;

    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['title', 'description', 'status', 'date_range', 'created_from', 'created_to'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $applyUserFilter = false)
    {
        $query = Task::find();

        if ($applyUserFilter) {
            $query->andWhere(['user_id' => Yii::$app->user->id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($this->date_range) && strpos($this->date_range, ' to ') !== false) {
            list($this->created_from, $this->created_to) = explode(' to ', $this->date_range);
        }

        if ($this->created_from && $this->created_to) {
            $query->andWhere(['between', 'created_at', $this->created_from . ' 00:00:00', $this->created_to . ' 23:59:59']);
        }

        if (!empty($params['TaskSearch']['user_id'])) {
            $query->andFilterWhere(['user_id' => $params['TaskSearch']['user_id']]);
        }

        $query->andFilterWhere(['status' => $this->status])
              ->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
