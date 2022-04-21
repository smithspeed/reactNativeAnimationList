import { StyleSheet, Text, View } from 'react-native'
import React from 'react'
import {N,SQUARE_SIZE} from '../constants';
import Animated, { useAnimatedStyle, useDerivedValue, withSpring, withTiming } from 'react-native-reanimated';

const Square = (props) => {

    const offsetAngle = (2 * Math.PI ) / N;

    const finalAngle = offsetAngle * (N - 1 - props.index);

    const rotate = useDerivedValue(() => {

        if(props.progress.value <= 2 * Math.PI){
            return Math.min(finalAngle, props.progress.value);
        }

        if(props.progress.value - 2 * Math.PI < finalAngle){
            return finalAngle;
        }

        return props.progress.value;

    },[]);

    const translateY = useDerivedValue(() => {

        if(rotate.value === finalAngle){
            return withSpring(-N * SQUARE_SIZE);
        }

        if(props.progress.value > 2 * Math.PI){
            return withTiming((props.index - N) * SQUARE_SIZE);
        }

        return  withTiming(-props.index * SQUARE_SIZE);
    })

    const animatedStyle = useAnimatedStyle(() => {

        return {
            transform : [
                { rotate : `${rotate.value}rad`},
                {translateY : translateY.value},
            ]
        }
    })

    return (
        <Animated.View
            style={[
                {
                    height : SQUARE_SIZE,
                    aspectRatio : 1,
                    backgroundColor : 'white',
                    //opacity : (props.index + 1) / N,
                    position : 'absolute',
                    
                },
                animatedStyle
            ]}
        />
    )
}

export default Square

const styles = StyleSheet.create({})