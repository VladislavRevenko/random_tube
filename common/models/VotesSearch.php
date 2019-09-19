<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Votes;

/**
 * VotesSearch represents the model behind the search form of `common\models\Votes`.
 */
class VotesSearch extends Votes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'video_id', 'vote'], 'integer'],
            [['ip', 'useragent', 'created'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Votes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'video_id' => $this->video_id,
            'vote' => $this->vote,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'useragent', $this->useragent]);

        return $dataProvider;
    }
}
